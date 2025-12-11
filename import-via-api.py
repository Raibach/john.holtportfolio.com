#!/usr/bin/env python3
"""
Railway Database Import via API
Uses Railway API token to import database
"""

import requests
import json
import time
import sys

RAILWAY_TOKEN = "86f7f343-9a8e-4d8d-93ef-3b07ac2ccb51"
API_BASE = "https://api.railway.app/v1"

headers = {
    "Authorization": f"Bearer {RAILWAY_TOKEN}",
    "Content-Type": "application/json"
}

print("🚂 Attempting Railway API import...")

# Try to get projects
try:
    response = requests.get(f"{API_BASE}/projects", headers=headers)
    print(f"API Response: {response.status_code}")
    print(f"Response: {response.text[:200]}")
except Exception as e:
    print(f"Error: {e}")

print("\n⚠️  Railway's API doesn't support direct database imports.")
print("The database must be imported via:")
print("1. Railway CLI tunnel (requires browser login)")
print("2. Railway web interface (manual)")
print("3. External database client connecting to TCP proxy")
print("\n✅ Your import script is ready at: import-database-final.sh")
print("   It just needs: railway login (one-time browser auth)")

