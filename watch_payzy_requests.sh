#!/bin/bash

echo "🔍 PayZY Request Monitor & Debugger"
echo "==================================="
echo ""
echo "This will show EXACTLY what data is being sent to PayZY API"
echo "Watching Laravel logs in real-time..."
echo ""
echo "Press Ctrl+C to stop"
echo ""

cd /Users/kaviya/Documents/crowlk/axita

# Clear log file for cleaner output (optional)
# > storage/logs/laravel.log

tail -f storage/logs/laravel.log | while read line; do
    # Highlight PayZY-related logs
    if [[ "$line" == *"PAYZY"* ]]; then
        # Color coding
        if [[ "$line" == *"ERROR"* ]]; then
            echo -e "\033[1;31m$line\033[0m"  # Red for errors
        elif [[ "$line" == *"WARNING"* ]] || [[ "$line" == *"⚠️"* ]]; then
            echo -e "\033[1;33m$line\033[0m"  # Yellow for warnings
        elif [[ "$line" == *"SUCCESS"* ]] || [[ "$line" == *"✅"* ]]; then
            echo -e "\033[1;32m$line\033[0m"  # Green for success
        elif [[ "$line" == *"==="* ]]; then
            echo -e "\033[1;36m$line\033[0m"  # Cyan for headers
        elif [[ "$line" == *"Configuration"* ]] || [[ "$line" == *"Credentials"* ]]; then
            echo -e "\033[1;35m$line\033[0m"  # Magenta for config
        elif [[ "$line" == *"Signature"* ]]; then
            echo -e "\033[1;34m$line\033[0m"  # Blue for signature
        elif [[ "$line" == *"Data Sent to PayZY"* ]]; then
            echo -e "\033[1;93m$line\033[0m"  # Bright yellow for request data
        elif [[ "$line" == *"API Response"* ]]; then
            echo -e "\033[1;96m$line\033[0m"  # Bright cyan for response
        else
            echo -e "\033[0;37m$line\033[0m"  # White for other logs
        fi
    fi
done
