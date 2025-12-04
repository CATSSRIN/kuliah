import cv2
import numpy as np

def smooth_sticker_edges(image_path, output_path, smoothing_amount=3):
    # 1. Load the image
    img = cv2.imread(image_path)
    if img is None:
        print("Error: Could not load image.")
        return

    # 2. Create a Mask
    # Convert to grayscale to find the bright sticker against the dark background
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    
    # Threshold: Anything brighter than pure black (value 5) becomes white
    _, thresh = cv2.threshold(gray, 5, 255, cv2.THRESH_BINARY)

    # 3. Find the sticker contour
    # This finds the outer shape and ignores holes inside the sticker
    contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    
    if not contours:
        print("Could not find the sticker outline.")
        return

    # Get the largest contour (assuming it's the main sticker)
    largest_contour = max(contours, key=cv2.contourArea)

    # Create a clean "Perfect" mask on a black canvas
    mask = np.zeros_like(gray)
    cv2.drawContours(mask, [largest_contour], -1, 255, thickness=cv2.FILLED)

    # 4. Anti-Aliasing Magic
    # Step A: Erode slightly to "shave off" the jagged pixel tips
    # We use a small kernel size based on input choice
    kernel = np.ones((3, 3), np.uint8)
    eroded_mask = cv2.erode(mask, kernel, iterations=1)

    # Step B: Convert to float so we can have semi-transparent pixels (0.0 to 1.0)
    mask_float = eroded_mask.astype(np.float32) / 255.0

    # Step C: Gaussian Blur the mask to create soft edges
    # The kernel size (k) determines how soft the edge is
    k = smoothing_amount * 2 + 1  # Ensures number is always odd (3, 5, 7, etc)
    blurred_mask = cv2.GaussianBlur(mask_float, (k, k), 0)

    # 5. Apply the Soft Mask to the Image
    # Split the original image into Blue, Green, Red channels
    b, g, r = cv2.split(img)

    # Multiply each channel by our soft mask
    # This keeps the center solid but fades the edges into the black background
    b = (b * blurred_mask).astype(np.uint8)
    g = (g * blurred_mask).astype(np.uint8)
    r = (r * blurred_mask).astype(np.uint8)

    # Merge channels back together
    result = cv2.merge([b, g, r])

    # 6. Save the result
    cv2.imwrite(output_path, result)
    print(f"Smoothed image saved to: {output_path}")

# --- RUN THE CODE ---
# Replace 'sticker.png' with your actual filename
smooth_sticker_edges('sticker.png', 'smoothed_sticker.png', smoothing_amount=100)