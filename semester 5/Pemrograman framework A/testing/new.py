import cv2
import numpy as np

def simple_round_corners(image_path, output_path, roundness=7):
    # 1. Load the image
    img = cv2.imread(image_path)
    if img is None:
        print("Error: Could not load image.")
        return

    # 2. Get the sticker shape (Mask)
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    _, mask = cv2.threshold(gray, 10, 255, cv2.THRESH_BINARY)

    # 3. Round the jagged lines (The "Sanding" step)
    # We use an Ellipse shape to force the jagged pixels into a curve
    kernel = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (roundness, roundness))

    # MORPH_OPEN: This shaves off the sharp "staircase" spikes
    # It essentially shrinks the shape slightly, smoothing the outer edge
    smooth_mask = cv2.morphologyEx(mask, cv2.MORPH_OPEN, kernel)

    # MORPH_CLOSE: This fills in any sharp cracks
    smooth_mask = cv2.morphologyEx(smooth_mask, cv2.MORPH_CLOSE, kernel)

    # 4. Apply the new rounded mask to the ORIGINAL image
    # We convert the single-channel mask to 3-channel to match the image
    mask_3ch = cv2.merge([smooth_mask, smooth_mask, smooth_mask])

    # We use bitwise_and. This keeps the original pixels where the mask is white,
    # and turns them black where the mask is black. 
    # NO color blending, NO saturation changes.
    result = cv2.bitwise_and(img, mask_3ch)

    # 5. Save
    cv2.imwrite(output_path, result)
    print(f"Saved to: {output_path}")

# --- RUN THE CODE ---
# 'roundness' controls how much we sand the edges.
# 5 = slight rounding
# 15 = very round
simple_round_corners('sticker.png', 'rounded_simple.png', roundness=200)