# Claude Code CLI 安装与排错

## 目录

- [一、安装方式](#一安装方式)
- [二、验证安装](#二验证安装)
- [三、终端提示找不到 claude](#三终端提示找不到-claude)
- [四、API 连接 UNKNOWN_CERTIFICATE_VERIFICATION_ERROR](#四api-连接-unknown_certificate_verification_error)

---

## 一、安装方式

### 1. Windows（本机常见：通过 WinGet）

使用 WinGet 安装 **Anthropic Claude Code** 后，可执行文件通常位于用户目录下的 WinGet 包路径中，例如：

`%LOCALAPPDATA%\Microsoft\WinGet\Packages\Anthropic.ClaudeCode_Microsoft.Winget.Source_*\claude.exe`

（具体文件夹名可能随版本略有变化，以本机为准。）

### 2. 其他安装方式（可选）

- **官方 PowerShell 脚本**（需网络）：`irm https://claude.ai/install.ps1 | iex`
- **npm 全局安装**（需已安装 Node.js，且 `npm` 在 PATH 中）：  
  `npm install -g @anthropic-ai/claude-code`

安装完成后，建议将 **`%USERPROFILE%\.local\bin`** 或 WinGet 给出的目录加入**用户环境变量 Path**（若安装程序未自动添加）。

---

## 二、验证安装

在 **新的** PowerShell 窗口中执行：

```powershell
where.exe claude
claude --version
```

若能显示 `claude.exe` 路径且 `--version` 有输出，说明 CLI 已正确安装并可被终端找到。

---

## 三、终端提示找不到 claude

**现象**：`无法将“claude”项识别为 cmdlet、函数、脚本文件或可运行程序的名称`。

**常见原因**：已安装，但当前终端进程使用的 **PATH 仍是旧的**（例如安装或修改用户环境变量后，未重启 Cursor / 未新开终端）。

**处理步骤**：

1. **完全退出 Cursor**（确保进程结束），再重新打开，并新开终端。
2. 若仍不行，可在当前 PowerShell 会话中手动合并系统与用户 PATH 后再试：

```powershell
$env:Path = [System.Environment]::GetEnvironmentVariable("Path","Machine") + ";" + [System.Environment]::GetEnvironmentVariable("Path","User")
claude --version
```

3. **临时绕过 PATH**：使用 `where.exe claude` 或资源管理器找到 `claude.exe` 的完整路径，用完整路径运行。

---

## 四、API 连接 UNKNOWN_CERTIFICATE_VERIFICATION_ERROR

**现象**：连接 API 时出现 `UNKNOWN_CERTIFICATE_VERIFICATION_ERROR`，并伴随 “Retrying in 2s · attempt x/10” 等重试日志。

**含义**：客户端在建立 HTTPS 连接时 **TLS 证书校验未通过**。不一定是“证书错了”这一种情况，常见原因包括：

| 可能原因 | 说明 |
|----------|------|
| 公司/校园网络 HTTPS 解密 | 中间人代理使用**自签或企业根证书**，若系统或运行环境未信任该根证书，会校验失败。 |
| 杀毒或安全软件 | 部分软件对 HTTPS 做扫描，相当于本地代理，证书链与公网不一致。 |
| VPN / 系统代理 | 流量经代理时，若证书由代理签发且未受信任，会报错。 |
| 系统时间错误 | 系统日期时间偏差过大时，证书有效期校验会失败。 |
| 根证书/系统信任库异常 | Windows 证书存储被精简、损坏或策略限制。 |

**建议排查顺序**：

1. 核对 **Windows 日期与时间**、时区是否正确。
2. 若在**公司网络**，联系管理员是否需安装 **企业根证书**，或是否必须走指定代理；代理场景需保证 Claude Code / 终端使用的环境也配置了可信证书或正确的 `HTTPS_PROXY` 等。
3. 暂时关闭或排除 **杀毒/SSL 扫描**（仅用于定位问题，确认后再恢复安全策略）。
4. 换网络对比（例如手机热点与当前局域网），判断是否与当前网络策略有关。

**注意**：`--dangerously-skip-permissions` 只影响 Claude Code **本地工具权限**相关行为，**不能**用来绕过 HTTPS 证书校验；证书问题需从网络、代理、系统信任与时间等方面解决。

---

*文档整理自本仓库环境下的安装与排错记录，具体路径与版本以你本机为准。*
