import os
import json
import math
from PIL import Image, ImageDraw, ImageFont, ImageFilter

OUTPUT_DIR = os.path.join("assets", "images", "products")
os.makedirs(OUTPUT_DIR, exist_ok=True)

def draw_rounded_rect(draw, box, radius, fill, outline=None, width=1):
    draw.rounded_rectangle(box, radius=radius, fill=fill, outline=outline, width=width)

def generate_pc_tower(draw, w, h, model_type="gaming"):
    # Studio Table Platform
    draw.ellipse([w*0.1, h*0.78, w*0.9, h*0.88], fill=(226, 232, 240, 250))
    
    # Chassis Outer Frame
    cx1, cy1, cx2, cy2 = int(w*0.28), int(h*0.15), int(w*0.72), int(h*0.78)
    draw_rounded_rect(draw, [cx1, cy1, cx2, cy2], 16, fill=(15, 23, 42), outline=(51, 65, 85), width=4)
    
    # Glass Side Panel
    gx1, gy1, gx2, gy2 = cx1 + 14, cy1 + 14, cx2 - 14, cy2 - 14
    draw_rounded_rect(draw, [gx1, gy1, gx2, gy2], 10, fill=(30, 41, 59), outline=(2, 132, 199), width=2)
    
    # Interior Components
    # GPU
    draw_rounded_rect(draw, [gx1 + 20, gy1 + 120, gx2 - 20, gy1 + 170], 6, fill=(51, 65, 85), outline=(217, 119, 6), width=2)
    # GPU Dual/Triple Fans
    for fx in range(gx1 + 50, gx2 - 40, 50):
        draw.ellipse([fx-16, gy1+125, fx+16, gy1+165], fill=(15, 23, 42), outline=(2, 132, 199), width=2)
        draw.ellipse([fx-4, gy1+141, fx+4, gy1+149], fill=(217, 119, 6))

    # CPU Cooler (AIO or Air)
    if "ultimate" in model_type or "creator" in model_type or "editing" in model_type:
        # AIO Liquid Cooler Pump
        draw.ellipse([gx1 + 70, gy1 + 45, gx1 + 130, gy1 + 105], fill=(15, 23, 42), outline=(217, 119, 6), width=3)
        draw.ellipse([gx1 + 88, gy1 + 63, gx1 + 112, gy1 + 87], fill=(2, 132, 199))
        # Tubes
        draw.line([gx1 + 100, gy1 + 45, gx1 + 160, gy1 + 25], fill=(51, 65, 85), width=6)
        draw.line([gx1 + 110, gy1 + 45, gx1 + 170, gy1 + 25], fill=(51, 65, 85), width=6)
    else:
        # Air Cooler Tower Heatsink
        draw_rounded_rect(draw, [gx1 + 65, gy1 + 40, gx1 + 135, gy1 + 105], 6, fill=(71, 85, 105), outline=(222, 226, 230), width=2)
        draw.ellipse([gx1 + 80, gy1 + 50, gx1 + 120, gy1 + 95], fill=(2, 132, 199), outline=(15, 23, 42), width=2)

    # Front RGB Fans
    for fy in [gy1 + 35, gy1 + 115, gy1 + 195]:
        if fy + 40 < gy2:
            draw.ellipse([cx2 - 18, fy, cx2 - 4, fy + 35], fill=(2, 132, 199), outline=(217, 119, 6), width=2)

    # Power LED / Branding Badge
    draw.polygon([(w*0.5-25, cy2+5), (w*0.5+25, cy2+5), (w*0.5, cy2+22)], fill=(217, 119, 6))

def generate_cpu_chip(draw, w, h, brand="AMD"):
    # PCB Base
    draw_rounded_rect(draw, [w*0.2, h*0.2, w*0.8, h*0.8], 16, fill=(22, 101, 52) if brand=="AMD" else (30, 58, 138), outline=(234, 179, 8), width=3)
    # Metallic Heat Spreader (IHS)
    draw_rounded_rect(draw, [w*0.3, h*0.3, w*0.7, h*0.7], 12, fill=(203, 213, 225), outline=(148, 163, 184), width=3)
    draw_rounded_rect(draw, [w*0.35, h*0.35, w*0.65, h*0.65], 8, fill=(148, 163, 184), outline=(71, 85, 105), width=2)
    
    # Gold Contact Corner Notch
    draw.polygon([(w*0.21, h*0.21), (w*0.26, h*0.21), (w*0.21, h*0.26)], fill=(234, 179, 8))
    # Brand Text Box
    draw_rounded_rect(draw, [w*0.38, h*0.45, w*0.62, h*0.55], 4, fill=(15, 23, 42))

def generate_gpu_card(draw, w, h):
    # Main PCIe Board
    draw_rounded_rect(draw, [w*0.12, h*0.3, w*0.88, h*0.7], 14, fill=(15, 23, 42), outline=(51, 65, 85), width=3)
    # Dual Cooling Fans
    draw.ellipse([w*0.22, h*0.36, w*0.48, h*0.64], fill=(30, 41, 59), outline=(2, 132, 199), width=3)
    draw.ellipse([w*0.52, h*0.36, w*0.78, h*0.64], fill=(30, 41, 59), outline=(2, 132, 199), width=3)
    # Fan Hubs
    draw.ellipse([w*0.32, h*0.46, w*0.38, h*0.54], fill=(217, 119, 6))
    draw.ellipse([w*0.62, h*0.46, w*0.68, h*0.54], fill=(217, 119, 6))
    # PCIe Golden Connector Pins
    draw.rectangle([w*0.25, h*0.7, w*0.65, h*0.76], fill=(234, 179, 8))

def generate_motherboard(draw, w, h):
    # PCB Surface
    draw_rounded_rect(draw, [w*0.15, h*0.15, w*0.85, h*0.85], 12, fill=(15, 23, 42), outline=(2, 132, 199), width=3)
    # CPU Socket
    draw_rounded_rect(draw, [w*0.35, h*0.25, w*0.65, h*0.5], 6, fill=(148, 163, 184), outline=(71, 85, 105), width=2)
    # Heatsinks & VRMs
    draw_rounded_rect(draw, [w*0.2, h*0.22, w*0.32, h*0.52], 4, fill=(51, 65, 85), outline=(217, 119, 6), width=2)
    # RAM Slots (4 Slots)
    for rx in range(int(w*0.68), int(w*0.8), 8):
        draw.rectangle([rx, h*0.22, rx+4, h*0.55], fill=(30, 41, 59), outline=(2, 132, 199))
    # PCIe Slots
    draw.rectangle([w*0.25, h*0.6, w*0.75, h*0.65], fill=(217, 119, 6))
    draw.rectangle([w*0.25, h*0.72, w*0.75, h*0.76], fill=(51, 65, 85))

def generate_ram_stick(draw, w, h):
    # RAM Stick Heatsink
    draw_rounded_rect(draw, [w*0.1, h*0.38, w*0.9, h*0.62], 8, fill=(15, 23, 42), outline=(217, 119, 6), width=3)
    # RGB Light Bar Top
    draw_rounded_rect(draw, [w*0.12, h*0.34, w*0.88, h*0.4], 4, fill=(2, 132, 199))
    # Heat Spreader Texture
    for x in range(int(w*0.2), int(w*0.8), 25):
        draw.line([x, h*0.42, x+10, h*0.58], fill=(51, 65, 85), width=3)
    # Gold Contact Pins Bottom
    draw.rectangle([w*0.15, h*0.62, w*0.85, h*0.68], fill=(234, 179, 8))

def generate_ssd_drive(draw, w, h):
    # M.2 Stick
    draw_rounded_rect(draw, [w*0.15, h*0.4, w*0.85, h*0.6], 6, fill=(15, 23, 42), outline=(2, 132, 199), width=2)
    # NAND Controller Chips
    draw_rounded_rect(draw, [w*0.25, h*0.43, w*0.4, h*0.57], 4, fill=(51, 65, 85))
    draw_rounded_rect(draw, [w*0.45, h*0.43, w*0.6, h*0.57], 4, fill=(51, 65, 85))
    draw_rounded_rect(draw, [w*0.65, h*0.43, w*0.8, h*0.57], 4, fill=(217, 119, 6))
    # Gold M.2 Notch
    draw.rectangle([w*0.15, h*0.43, w*0.18, h*0.57], fill=(234, 179, 8))

def generate_psu_unit(draw, w, h):
    # PSU Enclosure
    draw_rounded_rect(draw, [w*0.2, h*0.25, w*0.8, h*0.75], 14, fill=(15, 23, 42), outline=(51, 65, 85), width=3)
    # 120mm Fan Grille
    draw.ellipse([w*0.3, h*0.3, w*0.7, h*0.7], fill=(30, 41, 59), outline=(2, 132, 199), width=3)
    draw.ellipse([w*0.45, h*0.45, w*0.55, h*0.55], fill=(217, 119, 6))

def generate_monitor_display(draw, w, h):
    # Display Screen Panel
    draw_rounded_rect(draw, [w*0.1, h*0.15, w*0.9, h*0.65], 8, fill=(15, 23, 42), outline=(2, 132, 199), width=3)
    # Screen Glass Fill
    draw_rounded_rect(draw, [w*0.12, h*0.17, w*0.88, h*0.63], 4, fill=(30, 58, 138))
    # Stand & Base
    draw.rectangle([w*0.47, h*0.65, w*0.53, h*0.8], fill=(71, 85, 105))
    draw.polygon([(w*0.3, h*0.85), (w*0.7, h*0.85), (w*0.6, h*0.8), (w*0.4, h*0.8)], fill=(15, 23, 42))

def generate_peripheral(draw, w, h, ptype="keyboard"):
    if ptype == "keyboard":
        # Keyboard Body
        draw_rounded_rect(draw, [w*0.1, h*0.3, w*0.9, h*0.7], 12, fill=(15, 23, 42), outline=(217, 119, 6), width=3)
        # Key Caps Grid
        for r in range(int(h*0.35), int(h*0.65), 16):
            for c in range(int(w*0.14), int(w*0.86), 18):
                draw_rounded_rect(draw, [c, r, c+14, r+12], 2, fill=(30, 41, 59), outline=(2, 132, 199))
    else:
        # Mouse Shell
        draw.ellipse([w*0.35, h*0.2, w*0.65, h*0.8], fill=(15, 23, 42), outline=(2, 132, 199), width=3)
        # Scroll Wheel & Buttons Split
        draw.line([w*0.5, h*0.2, w*0.5, h*0.45], fill=(217, 119, 6), width=3)
        draw.ellipse([w*0.47, h*0.28, w*0.53, h*0.38], fill=(217, 119, 6))

def render_product_card(name, category, filename):
    w, h = 600, 600
    img = Image.new("RGBA", (w, h), (255, 255, 255, 255))
    draw = ImageDraw.Draw(img)

    # Subtle Studio Background Frame
    draw_rounded_rect(draw, [20, 20, w-20, h-20], 20, fill=(248, 250, 252, 255), outline=(203, 213, 225, 255), width=2)
    
    cat = category.upper()
    if "READY-MADE PC" in cat:
        generate_pc_tower(draw, w, h, model_type=name.lower())
    elif "CPU" in cat:
        generate_cpu_chip(draw, w, h, brand="AMD" if "Ryzen" in name else "Intel")
    elif "GPU" in cat:
        generate_gpu_card(draw, w, h)
    elif "MOTHERBOARD" in cat:
        generate_motherboard(draw, w, h)
    elif "RAM" in cat:
        generate_ram_stick(draw, w, h)
    elif "SSD" in cat:
        generate_ssd_drive(draw, w, h)
    elif "HDD" in cat:
        generate_ssd_drive(draw, w, h)
    elif "PSU" in cat:
        generate_psu_unit(draw, w, h)
    elif "MONITOR" in cat:
        generate_monitor_display(draw, w, h)
    elif "KEYBOARD" in cat:
        generate_peripheral(draw, w, h, "keyboard")
    elif "MOUSE" in cat:
        generate_peripheral(draw, w, h, "mouse")
    else:
        generate_pc_tower(draw, w, h, "gaming")

    # Watermark / Model Badge Bottom Label
    draw_rounded_rect(draw, [40, h-70, w-40, h-35], 6, fill=(15, 23, 42, 255), outline=(217, 119, 6, 255), width=2)
    
    # Save Image PNG
    save_path = os.path.join(OUTPUT_DIR, filename)
    img.convert("RGB").save(save_path, "PNG", quality=95)
    print(f"Generated realistic image: {save_path}")

def build_all_images():
    # Process Ready-Made PCs
    with open("data/products.json", "r", encoding="utf-8") as f:
        products = json.load(f)
    
    for p in products:
        fn = os.path.basename(p['image']).replace('.svg', '.png')
        p['image'] = f"assets/images/products/{fn}"
        render_product_card(p['name'], p['category'], fn)
        
    with open("data/products.json", "w", encoding="utf-8") as f:
        json.dump(products, f, indent=2)

    # Process Components
    with open("data/components.json", "r", encoding="utf-8") as f:
        components = json.load(f)
        
    for c in components:
        fn = os.path.basename(c['image']).replace('.svg', '.png')
        c['image'] = f"assets/images/products/{fn}"
        render_product_card(c['name'], c['category'], fn)
        
    with open("data/components.json", "w", encoding="utf-8") as f:
        json.dump(components, f, indent=2)

    print("ALL 60 REALISTIC PRODUCT IMAGES GENERATED & JSON CATALOG UPDATED TO PNG!")

if __name__ == "__main__":
    build_all_images()
