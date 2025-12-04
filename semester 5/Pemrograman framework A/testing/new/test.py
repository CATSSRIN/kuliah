import cv2
import numpy as np
from rembg import remove
from PIL import Image, ImageOps

def create_sticker_style(image_path, output_path, outline_width=15, outline_color=(255, 255, 255)):
    """
    Removes background and adds a sticker-like outline.
    
    :param image_path: Path to input image
    :param output_path: Path to save output image
    :param outline_width: Thickness of the outline (in pixels)
    :param outline_color: RGB tuple for the outline color (Default is White)
    """
    print("Step 1: Loading image and removing background...")
    
    # 1. Load image and remove background using rembg
    try:
        input_img = Image.open(image_path)
        
        # 'remove' uses AI to strip the background cleanly
        # post_process_mask=True helps smooth jagged edges
        subject = remove(input_img, post_process_mask=True)
    except Exception as e:
        print(f"Error loading or removing background: {e}")
        return

    print("Step 2: Generating outline...")

    # 2. Convert PIL image to NumPy array for OpenCV processing
    # We need to extract the Alpha channel (transparency) to define the shape
    subject_np = np.array(subject)
    alpha_channel = subject_np[:, :, 3]

    # 3. Dilate the alpha channel to create the thickened silhouette
    # This expands the white area of the mask by 'outline_width'
    kernel = np.ones((outline_width, outline_width), np.uint8)
    dilated_mask = cv2.dilate(alpha_channel, kernel, iterations=1)

    # 4. Create the outline layer
    # Create a blank image with the same dimensions
    outline_layer_np = np.zeros_like(subject_np)
    
    # Wherever the dilated mask is white, set the pixel to the outline color
    # We apply the color to R, G, B channels
    outline_layer_np[:, :, 0][dilated_mask > 0] = outline_color[0] # Red
    outline_layer_np[:, :, 1][dilated_mask > 0] = outline_color[1] # Green
    outline_layer_np[:, :, 2][dilated_mask > 0] = outline_color[2] # Blue
    
    # Set the Alpha channel: fully opaque (255) where the dilated mask exists
    outline_layer_np[:, :, 3] = dilated_mask

    # 5. Convert back to PIL
    outline_img = Image.fromarray(outline_layer_np)

    print("Step 3: Compositing final image...")

    # 6. Paste the original subject ON TOP of the outline layer
    # We use alpha_composite to ensure transparency is handled correctly
    final_result = Image.alpha_composite(outline_img, subject)

    # 7. Save the result
    final_result.save(output_path, format="PNG")
    print(f"Done! Saved to: {output_path}")

# --- CONFIGURATION ---
input_filename = "sticker.png" # Replace with your actual file path
output_filename = "Mizuki_Sticker_Output.png"

# Run the function
# You can change outline_width to make the border thicker/thinner
# You can change outline_color to (0,0,0) if you want a black border
create_sticker_style(
    input_filename, 
    output_filename, 
    outline_width=20, 
    outline_color=(255, 255, 255) # White outline
)