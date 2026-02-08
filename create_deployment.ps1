# Deployment script for FA_ProductAttributes_Core
# This creates a clean deployment package with only essential FA files

param(
    [string]$DeployDir = "deployment"
)

Write-Host "Creating FA_ProductAttributes_Core deployment package..." -ForegroundColor Green

# Create deployment directory
$deployPath = Join-Path $PSScriptRoot $DeployDir
$modulePath = Join-Path $deployPath "FA_ProductAttributes_Core"
$initPath = Join-Path $modulePath "_init"

New-Item -ItemType Directory -Path $initPath -Force | Out-Null

# Copy essential files
Copy-Item "_init\config" $initPath -Force
Copy-Item "hooks.php" $modulePath -Force
Copy-Item "product_attributes_admin.php" $modulePath -Force

Write-Host "Deployment package created in: $modulePath" -ForegroundColor Green
Write-Host ""
Write-Host "Files included:" -ForegroundColor Yellow
Get-ChildItem $modulePath -Recurse | Format-Table -AutoSize
Write-Host ""
Write-Host "To deploy to server:" -ForegroundColor Cyan
Write-Host "scp -r $modulePath user@server:/path/to/FrontAccounting/modules/"