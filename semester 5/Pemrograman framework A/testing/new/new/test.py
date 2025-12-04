import cv2
import numpy as np
from rembg import remove
from PIL import Image, ImageChops
import io

def process_sticker_clean(input_path, output_path, outline_width=15, outline_color=(255, 255, 255)):
    print(f"1. Reading {input_path}...")
    with open(input_path, 'rb') as i:
        input_data = i.read()
        
    # --- STEP 1: AI Object Detection ---
    print("2. Removing background...")
    subject_data = remove(input_data)
    subject_img = Image.open(io.BytesIO(subject_data)).convert("RGBA")

    # --- STEP 2: Clean the Edge (The Fix) ---
    print("3. Cleaning edges to remove shadow/fringe...")
    # Convert to NumPy to use OpenCV for erosion
    img_np = np.array(subject_img)
    
    # Extract Alpha (transparency) and RGB channels
    alpha = img_np[:, :, 3]
    rgb = img_np[:, :, 0:3]

    # Create an erosion kernel (a 3x3 square)
    # This will shrink the mask by 1 pixel on all sides
    erode_kernel = np.ones((3, 3), np.uint8)
    
    # 'Erode' shrinks the white area of the mask, cutting off the thin fringe
    eroded_alpha = cv2.erode(alpha, erode_kernel, iterations=1)
    
    # Create a cleaned subject image using the new, slightly smaller mask
    # This crops the original image to the new, cleaner shape
    cleaned_img_np = np.dstack((rgb, eroded_alpha))
    cleaned_subject_img = Image.fromarray(cleaned_img_np)

    # --- STEP 3: Create the Outline ---
    print("4. Generating smooth outline...")

    # Add padding to canvas so outline fits
    pad = outline_width + 10
    
    # Create padded mask for dilation from the ERODED mask
    alpha_padded = np.pad(eroded_alpha, pad, mode='constant', constant_values=0)
    
    # Dilation kernel for the outline (rounded)
    dilate_kernel = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (2*outline_width, 2*outline_width))
    
    # Dilate to create the sticker shape
    dilated_mask = cv2.dilate(alpha_padded, dilate_kernel, iterations=1)
    
    # Create the solid outline layer
    outline_layer = np.zeros((alpha_padded.shape[0], alpha_padded.shape[1], 4), dtype=np.uint8)
    outline_layer[:, :] = outline_color + (0,) # Set RGB, A=0 initially
    outline_layer[:, :, 3] = dilated_mask      # Set Alpha from dilated mask

    # --- STEP 4: Composite ---
    outline_pil = Image.fromarray(outline_layer)
    
    # Pad the CLEANED subject image to match the new size
    subject_padded = Image.new("RGBA", outline_pil.size, (0, 0, 0, 0))
    subject_padded.paste(cleaned_subject_img, (pad, pad))

    # Paste the cleaned subject ON TOP of the outline
    final_result = Image.alpha_composite(outline_pil, subject_padded)
    
    # Crop excess space
    bbox = final_result.getbbox()
    if bbox:
        final_result = final_result.crop(bbox)

    final_result.save(output_path)
    print(f"Done! Saved clean sticker to {output_path}")

# --- CONFIGURATION ---
input_file = "sticker.png"
output_file = "Mizuki_Sticker_Cleaned.png"

# Run the fixed function
process_sticker_clean(input_file, output_file, outline_width=20)