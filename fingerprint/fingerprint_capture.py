import time
import os
import base64
import json
from datetime import datetime
from pywinauto.application import Application
from pywinauto.keyboard import send_keys

# --- PATH CONFIG ---
EXE_PATH = r"C:\Users\Admin\Desktop\rectemchapel\fingerprint\ftrScanApiEx.exe"
OUTPUT_JSON = r"C:\Users\Admin\Desktop\rectemchapel\fingerprint\fingerprint_output.json"

def write_result(result):
    """Save result to JSON for Laravel"""
    try:
        with open(OUTPUT_JSON, "w") as f:
            f.write(json.dumps(result))
    except Exception as e:
        print(f"Error writing JSON: {e}")

def main():
    try:
        # Create unique save path each run
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
        save_path = rf"C:\Users\Admin\Desktop\rectemchapel\fingerprint\capture_{timestamp}.bmp"

        # Start Futronic demo app
        app = Application(backend="win32").start(EXE_PATH)
        time.sleep(2)

        # Connect to main window
        win = app.window(title_re=".*Futronic.*")

        # Automate menu sequence
        win.menu_select("Capture Finger->Scanning Functions->GetFrame Single")
        time.sleep(1)
        win.menu_select("Capture Finger->Start")
        time.sleep(3)

        # Save image
        win.menu_select("File->Save Image...")
        time.sleep(1)

        save_dialog = app.window(title_re="Save As")
        save_dialog.wait("visible", timeout=10)
        save_dialog.Edit.type_keys(save_path, with_spaces=True)
        send_keys("{ENTER}")
        time.sleep(2)

        # Close the app
        win.close()

        # Check file exists
        if not os.path.exists(save_path):
            result = {"success": False, "message": "Fingerprint not captured (file missing)."}
            write_result(result)
            return

        # Convert BMP → base64
        with open(save_path, "rb") as f:
            encoded = base64.b64encode(f.read()).decode("utf-8")

        result = {"success": True, "message": "Fingerprint captured", "data": encoded}
        write_result(result)

        # Optional: delete the saved bmp to avoid clutter
        try:
            os.remove(save_path)
        except:
            pass

    except Exception as e:
        result = {"success": False, "message": str(e)}
        write_result(result)

if __name__ == "__main__":
    main()
