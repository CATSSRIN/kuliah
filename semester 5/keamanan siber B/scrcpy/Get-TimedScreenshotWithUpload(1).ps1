Function Get-TimedScreenshot {
<#
.SYNOPSIS
  Ambil screenshot berkala dan opsional upload ke Google Drive (rclone) atau website.

.PARAMETER Path
  Folder tujuan penyimpanan file PNG (dibuat otomatis jika belum ada).

.PARAMETER Interval
  Interval detik antar screenshot (contoh 1800 untuk 30 menit).

.PARAMETER EndTime
  Waktu berhenti untuk hari ini, format HH:mm (contoh "23:59").

.PARAMETER UploadTo
  None | GDrive | Web

.PARAMETER RcloneRemote
  Nama remote rclone (contoh "gdrive"). Wajib jika UploadTo = GDrive.

.PARAMETER RemotePath
  Path folder tujuan di remote rclone (contoh "Screenshots"). Opsional.

.PARAMETER UploadUrl
  URL endpoint upload (wajib jika UploadTo = Web).

.PARAMETER UploadHeaders
  Hashtable header tambahan untuk upload web (contoh @{ Authorization = "Bearer 123" }).

.EXAMPLE
  Get-TimedScreenshot -Path C:\temp\shots -Interval 1800 -EndTime 23:59 -UploadTo GDrive -RcloneRemote gdrive -RemotePath "Screenshots"
#>

  [CmdletBinding()]
  Param(
    [Parameter(Mandatory=$True)]
    [string] $Path,

    [Parameter(Mandatory=$True)]
    [int] $Interval,

    [Parameter(Mandatory=$True)]
    [string] $EndTime,

    [ValidateSet('None','GDrive','Web')]
    [string] $UploadTo = 'None',

    [string] $RcloneRemote,
    [string] $RemotePath = "",

    [string] $UploadUrl,
    [hashtable] $UploadHeaders
  )

  Function GenScreenshot([string]$OutputPath) {
    $ScreenBounds = [Windows.Forms.SystemInformation]::VirtualScreen
    $bmp = New-Object System.Drawing.Bitmap $ScreenBounds.Width, $ScreenBounds.Height
    $gfx = [System.Drawing.Graphics]::FromImage($bmp)
    $gfx.CopyFromScreen($ScreenBounds.Location, [System.Drawing.Point]::Empty, $ScreenBounds.Size)
    $gfx.Dispose()
    $bmp.Save($OutputPath, [System.Drawing.Imaging.ImageFormat]::Png)
    $bmp.Dispose()
  }

  Function Upload-IfNeeded([string]$FilePath) {
    switch ($UploadTo) {
      'GDrive' {
        if (-not $RcloneRemote) {
          Write-Warning "RcloneRemote wajib diisi untuk UploadTo=GDrive"
          break
        }
        $dest = if ([string]::IsNullOrWhiteSpace($RemotePath)) { "$RcloneRemote:" } else { "$RcloneRemote:$RemotePath" }
        try {
          $psi = New-Object System.Diagnostics.ProcessStartInfo
          $psi.FileName = "rclone"
          $psi.Arguments = "copy `"$FilePath`" `"$dest`" --transfers 1 --no-traverse --retries 1 --low-level-retries 1 --stats 0"
          $psi.RedirectStandardOutput = $true
          $psi.RedirectStandardError = $true
          $psi.UseShellExecute = $false
          $p = [System.Diagnostics.Process]::Start($psi)
          $p.WaitForExit()
          if ($p.ExitCode -ne 0) {
            $err = $p.StandardError.ReadToEnd()
            Write-Warning "rclone gagal (code $($p.ExitCode)): $err"
          } else {
            Write-Verbose "Upload ke GDrive sukses: $dest"
          }
        } catch {
          Write-Warning "Gagal menjalankan rclone: $($_.Exception.Message)"
        }
      }
      'Web' {
        if (-not $UploadUrl) {
          Write-Warning "UploadUrl wajib diisi untuk UploadTo=Web"
          break
        }
        $wc = New-Object System.Net.WebClient
        try {
          if ($UploadHeaders) {
            foreach ($k in $UploadHeaders.Keys) { $wc.Headers[$k] = [string]$UploadHeaders[$k] }
          }
          # WebClient.UploadFile mengirim multipart/form-data secara otomatis
          $respBytes = $wc.UploadFile($UploadUrl, 'POST', $FilePath)
          $respText = [Text.Encoding]::UTF8.GetString($respBytes)
          Write-Verbose "Upload Web sukses. Respons: $respText"
        } catch {
          Write-Warning "Upload Web gagal: $($_.Exception.Message)"
        } finally {
          $wc.Dispose()
        }
      }
      Default { }
    }
  }

  try {
    Add-Type -AssemblyName System.Windows.Forms
    Add-Type -AssemblyName System.Drawing

    if (-not (Test-Path -Path $Path)) {
      New-Item -ItemType Directory -Path $Path -Force | Out-Null
    }

    if ($Interval -le 0) { throw "Interval harus > 0" }
    if ($EndTime -notmatch '^\d{2}:\d{2}$') { throw "EndTime harus format HH:mm, contoh 23:59" }

    $now = Get-Date
    $h, $m = $EndTime.Split(':')
    $stopTime = Get-Date -Year $now.Year -Month $now.Month -Day $now.Day -Hour $h -Minute $m -Second 0

    Do {
      $time = Get-Date
      $fileName = "{0:MM-dd-yyyy-HH-mm-ss}.png" -f $time
      $filePath = Join-Path $Path $fileName

      GenScreenshot -OutputPath $filePath
      Write-Verbose "Saved screenshot to $filePath"

      Upload-IfNeeded -FilePath $filePath

      Write-Verbose "Sleeping for $Interval seconds"
      Start-Sleep -Seconds $Interval
    } While ((Get-Date) -lt $stopTime)
  }
  catch {
    Write-Warning ( $_.Exception.Message + "`n" + $_.InvocationInfo.PositionMessage )
  }
}