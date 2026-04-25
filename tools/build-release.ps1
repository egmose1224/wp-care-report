param(
    [string] $Version = "0.1.0"
)

$ErrorActionPreference = "Stop"

$root = Split-Path -Parent $PSScriptRoot
$dist = Join-Path $root ".dist"
$packageRoot = Join-Path $dist "wp-care-report"
$zipPath = Join-Path $dist "wp-care-report-$Version.zip"

if (Test-Path $packageRoot) {
    Remove-Item -LiteralPath $packageRoot -Recurse -Force
}

New-Item -ItemType Directory -Force -Path $packageRoot | Out-Null

$excludeDirectories = @(
    ".git",
    ".github",
    ".demo-wp",
    ".demo-wp-db",
    ".dist",
    "dist",
    "tools",
    "node_modules",
    "vendor"
)

$excludeFiles = @(
    ".gitignore",
    "agents.md"
)

Get-ChildItem -LiteralPath $root -Force | ForEach-Object {
    if ($excludeDirectories -contains $_.Name) {
        return
    }

    if ($excludeFiles -contains $_.Name) {
        return
    }

    $destination = Join-Path $packageRoot $_.Name
    Copy-Item -LiteralPath $_.FullName -Destination $destination -Recurse -Force
}

if (Test-Path $zipPath) {
    Remove-Item -LiteralPath $zipPath -Force
}

Compress-Archive -Path $packageRoot -DestinationPath $zipPath -Force

Write-Host "Built $zipPath"
