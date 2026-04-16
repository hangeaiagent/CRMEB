<?php

namespace crmeb\command;

use GuzzleHttp\Client;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;
use think\facade\Env;

/**
 * 从 Unsplash 合法拉取珍珠类目 demo 图片。
 * UNSPLASH_ACCESS_KEY 需要在 .env 配置。
 * 用法: php think pearl:image --query "akoya pearl necklace" --count 10
 */
class PearlImage extends Command
{
    protected function configure()
    {
        $this->setName('pearl:image')
            ->setDescription('Fetch demo pearl images from Unsplash API into public/uploads and register in system_attachment')
            ->addOption('query', null, Option::VALUE_REQUIRED, '搜索关键词', 'pearl necklace')
            ->addOption('count', null, Option::VALUE_REQUIRED, '拉取数量（最大30）', 10)
            ->addOption('category', null, Option::VALUE_REQUIRED, '图片分类名（写入 system_attachment_category 时使用）', 'pearl')
            ->addOption('register', null, Option::VALUE_NONE, '下载后写入 eb_system_attachment 使其在后台图库可见');
    }

    protected function execute(Input $input, Output $output)
    {
        $accessKey = Env::get('unsplash.access_key') ?: getenv('UNSPLASH_ACCESS_KEY');
        if (!$accessKey) {
            $output->error('未配置 UNSPLASH_ACCESS_KEY（.env 中加 [UNSPLASH] access_key=xxx）');
            return 1;
        }

        $query = (string)$input->getOption('query');
        $count = min(30, max(1, (int)$input->getOption('count')));
        $register = (bool)$input->getOption('register');

        $output->writeln("<info>query=</info>{$query} <info>count=</info>{$count}");

        $client = new Client(['timeout' => 20]);
        $resp = $client->get('https://api.unsplash.com/search/photos', [
            'headers' => ['Accept-Version' => 'v1', 'Authorization' => 'Client-ID ' . $accessKey],
            'query'   => ['query' => $query, 'per_page' => $count, 'orientation' => 'squarish'],
        ]);

        $data = json_decode((string)$resp->getBody(), true);
        if (empty($data['results'])) {
            $output->warning('Unsplash 未返回结果');
            return 0;
        }

        $date = date('Ymd');
        $relDir = 'uploads/attach/' . date('Y') . '/' . date('m') . '/' . $date;
        $absDir = app()->getRootPath() . 'public/' . $relDir;
        if (!is_dir($absDir) && !mkdir($absDir, 0755, true) && !is_dir($absDir)) {
            $output->error('无法创建目录: ' . $absDir);
            return 1;
        }

        $saved = 0;
        foreach ($data['results'] as $i => $photo) {
            $downloadUrl = $photo['urls']['regular'] ?? null;
            if (!$downloadUrl) continue;

            $filename = sprintf('pearl_%s_%s_%d.jpg', date('His'), substr(md5($photo['id']), 0, 8), $i);
            $absPath  = $absDir . '/' . $filename;
            $relPath  = '/' . $relDir . '/' . $filename;

            try {
                $bin = (string)$client->get($downloadUrl)->getBody();
                file_put_contents($absPath, $bin);

                if ($register) {
                    Db::name('system_attachment')->insert([
                        'name'        => $filename,
                        'att_dir'     => $relPath,
                        'satt_dir'    => $relPath,
                        'att_size'    => (string)strlen($bin),
                        'att_type'    => 'image/jpeg',
                        'pid'         => 1, // 商品图片
                        'time'        => time(),
                        'image_type'  => 1,
                        'module_type' => 1,
                        'real_name'   => $photo['id'] . '.jpg',
                        'type'        => 0,
                    ]);
                }

                // Unsplash 要求下载后 ping 一下以归因创作者
                if (!empty($photo['links']['download_location'])) {
                    $client->getAsync($photo['links']['download_location'], [
                        'headers' => ['Authorization' => 'Client-ID ' . $accessKey],
                    ]);
                }

                $output->writeln(sprintf('[%d/%d] %s by %s', ++$saved, $count, $relPath, $photo['user']['name'] ?? '?'));
            } catch (\Throwable $e) {
                $output->warning('下载失败: ' . $e->getMessage());
            }
        }

        $output->info("完成，共保存 {$saved} 张");
        return 0;
    }
}
