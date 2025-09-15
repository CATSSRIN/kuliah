from PIL import Image
import os

def convert_all_jpegs_to_png(folder_path):
    """
    Converts all JPEG images in a specified folder to PNG format.
    """
    # Check if the folder exists
    if not os.path.isdir(folder_path):
        print(f"Error: Folder not found at {folder_path}")
        return

    print(f"Starting conversion for files in: {folder_path}")

    # Loop through all files in the directory
    for filename in os.listdir(folder_path):
        # Check if the file is a JPEG
        if filename.lower().endswith(('.jpg', '.jpeg')):
            jpeg_path = os.path.join(folder_path, filename)
            
            try:
                # Open the JPEG image
                img = Image.open(jpeg_path)
                
                # Create the new PNG file path with the same name
                png_filename = os.path.splitext(filename)[0] + '.png'
                png_path = os.path.join(folder_path, png_filename)

                # Save the image as a PNG
                img.save(png_path, 'PNG')
                print(f"Converted '{filename}' to '{png_filename}'")
                
            except Exception as e:
                print(f"Failed to convert '{filename}': {e}")
    
    print("\nConversion complete.")

# --- Example Usage ---
# Replace 'path/to/your/folder' with the actual path to your folder
# For example, on Windows: r'C:\Users\YourUser\Pictures\MyJPEGs'
# On macOS/Linux: '/Users/YourUser/Pictures/MyJPEGs'

folder_to_convert = r'D:\Github\kuliah\semester 5\Informatika medis A\tugas 1\chest_xray\train\NORMAL'
convert_all_jpegs_to_png(folder_to_convert)