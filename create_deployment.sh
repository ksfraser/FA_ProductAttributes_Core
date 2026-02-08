#!/bin/bash
# Deployment script for FA_ProductAttributes_Core
# This creates a clean deployment package with only essential FA files

set -e

echo "Creating FA_ProductAttributes_Core deployment package..."

# Create deployment directory
DEPLOY_DIR="deployment"
mkdir -p "$DEPLOY_DIR/FA_ProductAttributes_Core/_init"

# Copy essential files
cp "_init/config" "$DEPLOY_DIR/FA_ProductAttributes_Core/_init/"
cp "hooks.php" "$DEPLOY_DIR/FA_ProductAttributes_Core/"
cp "product_attributes_admin.php" "$DEPLOY_DIR/FA_ProductAttributes_Core/"

echo "Deployment package created in: $DEPLOY_DIR/FA_ProductAttributes_Core/"
echo ""
echo "Files included:"
ls -la "$DEPLOY_DIR/FA_ProductAttributes_Core/"
ls -la "$DEPLOY_DIR/FA_ProductAttributes_Core/_init/"
echo ""
echo "To deploy to server:"
echo "scp -r $DEPLOY_DIR/FA_ProductAttributes_Core/ user@server:/path/to/FrontAccounting/modules/"