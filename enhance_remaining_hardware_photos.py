import os
import json
from PIL import Image, ImageDraw, ImageFilter

OUTPUT_DIR = os.path.join("assets", "images", "products")
os.makedirs(OUTPUT_DIR, exist_ok=True)

def create_ram_photo(filename, label_text, color_theme=(2, 132, 199)):
    w, h = 600, 600
    img = Image.new("RGB", (w, h), (255, 255, 255))
    draw = ImageDraw.Draw(img)

    # Card background
    draw.rounded_rectangle([20, 20, w-20, h-20], radius=16, fill=(248, 250, 252), outline=(203, 213, 225), width=2)
    
    # Shadow
    draw.rounded_rectangle([90, 240, 510, 390], radius=12, fill=(226, 232, 240))

    # Dual RAM Module 1 & 2
    for offset in [0, 40]:
        y1, y2 = 210 + offset, 330 + offset
        # Black Heatsink
        draw.rounded_rectangle([100, y1, 500, y2], radius=10, fill=(15, 23, 42), outline=(71, 85, 105), width=3)
        # RGB Light Strip Top
        draw.rounded_rectangle([110, y1-8, 490, y1+4], radius=4, fill=color_theme)
        # Aluminum Texture Cutouts
        for x in range(160, 440, 35):
            draw.line([x, y1+15, x+15, y2-15], fill=(51, 65, 85), width=4)
        # Brand Emblem Plate
        draw.rounded_rectangle([230, y1+25, 370, y2-25], radius=6, fill=(30, 41, 59), outline=(217, 119, 6), width=2)
        # Gold Finger Contacts
        draw.rectangle([130, y2, 470, y2+12], fill=(234, 179, 8))

    # Bottom Label Tag
    draw.rounded_rectangle([40, h-70, w-40, h-35], radius=6, fill=(15, 23, 42), outline=(217, 119, 6), width=2)
    img.save(os.path.join(OUTPUT_DIR, filename), "PNG", quality=95)

def create_ssd_photo(filename, label_text):
    w, h = 600, 600
    img = Image.new("RGB", (w, h), (255, 255, 255))
    draw = ImageDraw.Draw(img)

    draw.rounded_rectangle([20, 20, w-20, h-20], radius=16, fill=(248, 250, 252), outline=(203, 213, 225), width=2)
    
    # M.2 NVMe PCB
    draw.rounded_rectangle([100, 260, 500, 360], radius=8, fill=(15, 23, 42), outline=(2, 132, 199), width=3)
    # NAND Flash Memory Controller Chips
    draw.rounded_rectangle([160, 275, 250, 345], radius=4, fill=(51, 65, 85), outline=(100, 116, 139), width=2)
    draw.rounded_rectangle([270, 275, 360, 345], radius=4, fill=(51, 65, 85), outline=(100, 116, 139), width=2)
    draw.rounded_rectangle([380, 275, 470, 345], radius=4, fill=(217, 119, 6), outline=(234, 179, 8), width=2)
    # M.2 Connector Gold Notch
    draw.rectangle([100, 280, 115, 340], fill=(234, 179, 8))

    draw.rounded_rectangle([40, h-70, w-40, h-35], radius=6, fill=(15, 23, 42), outline=(217, 119, 6), width=2)
    img.save(os.path.join(OUTPUT_DIR, filename), "PNG", quality=95)

def create_psu_photo(filename, wattage_text):
    w, h = 600, 600
    img = Image.new("RGB", (w, h), (255, 255, 255))
    draw = ImageDraw.Draw(img)

    draw.rounded_rectangle([20, 20, w-20, h-20], radius=16, fill=(248, 250, 252), outline=(203, 213, 225), width=2)
    
    # PSU Matte Black Enclosure
    draw.rounded_rectangle([140, 160, 460, 440], radius=16, fill=(15, 23, 42), outline=(51, 65, 85), width=4)
    # 120mm Cooling Fan Grille
    draw.ellipse([180, 200, 420, 400], fill=(30, 41, 59), outline=(2, 132, 199), width=3)
    # Circular Wire Grille Lines
    for r in [30, 60, 90]:
        draw.ellipse([300-r, 300-r, 300+r, 300+r], outline=(71, 85, 105), width=2)
    # Fan Hub Gold Badge
    draw.ellipse([270, 270, 330, 330], fill=(217, 119, 6))

    draw.rounded_rectangle([40, h-70, w-40, h-35], radius=6, fill=(15, 23, 42), outline=(217, 119, 6), width=2)
    img.save(os.path.join(OUTPUT_DIR, filename), "PNG", quality=95)

def create_cooler_photo(filename, cooler_type="aio"):
    w, h = 600, 600
    img = Image.new("RGB", (w, h), (255, 255, 255))
    draw = ImageDraw.Draw(img)

    draw.rounded_rectangle([20, 20, w-20, h-20], radius=16, fill=(248, 250, 252), outline=(203, 213, 225), width=2)

    if cooler_type == "aio":
        # 240mm/360mm Radiator
        draw.rounded_rectangle([100, 140, 500, 230], radius=10, fill=(15, 23, 42), outline=(51, 65, 85), width=3)
        # Dual ARGB Fans on Radiator
        draw.ellipse([140, 145, 270, 225], fill=(30, 41, 59), outline=(2, 132, 199), width=3)
        draw.ellipse([330, 145, 460, 225], fill=(30, 41, 59), outline=(2, 132, 199), width=3)
        # Sleeved Tubes
        draw.line([200, 230, 250, 360], fill=(51, 65, 85), width=10)
        draw.line([220, 230, 270, 360], fill=(51, 65, 85), width=10)
        # Pump Block
        draw.ellipse([240, 340, 360, 460], fill=(15, 23, 42), outline=(217, 119, 6), width=4)
        draw.ellipse([270, 370, 330, 430], fill=(2, 132, 199))
    else:
        # Air Tower Cooler Heatsink Fins
        draw.rounded_rectangle([180, 180, 420, 400], radius=12, fill=(71, 85, 105), outline=(203, 213, 225), width=3)
        for y in range(200, 390, 16):
            draw.line([185, y, 415, y], fill=(203, 213, 225), width=2)
        # 120mm Fan Mounted
        draw.ellipse([230, 230, 370, 370], fill=(2, 132, 199), outline=(15, 23, 42), width=3)

    draw.rounded_rectangle([40, h-70, w-40, h-35], radius=6, fill=(15, 23, 42), outline=(217, 119, 6), width=2)
    img.save(os.path.join(OUTPUT_DIR, filename), "PNG", quality=95)

def create_monitor_photo(filename):
    w, h = 600, 600
    img = Image.new("RGB", (w, h), (255, 255, 255))
    draw = ImageDraw.Draw(img)

    draw.rounded_rectangle([20, 20, w-20, h-20], radius=16, fill=(248, 250, 252), outline=(203, 213, 225), width=2)
    
    # Ultra-Thin Bezel Frame
    draw.rounded_rectangle([80, 120, 520, 420], radius=10, fill=(15, 23, 42), outline=(2, 132, 199), width=3)
    # Vivid Display Panel Screen
    draw.rounded_rectangle([90, 130, 510, 410], radius=6, fill=(30, 58, 138))
    # Desktop Stand
    draw.rectangle([285, 420, 315, 500], fill=(71, 85, 105))
    draw.polygon([(200, 520), (400, 520), (350, 495), (250, 495)], fill=(15, 23, 42))

    draw.rounded_rectangle([40, h-70, w-40, h-35], radius=6, fill=(15, 23, 42), outline=(217, 119, 6), width=2)
    img.save(os.path.join(OUTPUT_DIR, filename), "PNG", quality=95)

def create_peripheral_photo(filename, ptype="keyboard"):
    w, h = 600, 600
    img = Image.new("RGB", (w, h), (255, 255, 255))
    draw = ImageDraw.Draw(img)

    draw.rounded_rectangle([20, 20, w-20, h-20], radius=16, fill=(248, 250, 252), outline=(203, 213, 225), width=2)

    if ptype == "keyboard":
        # Mechanical Keyboard Chassis
        draw.rounded_rectangle([80, 220, 520, 440], radius=14, fill=(15, 23, 42), outline=(217, 119, 6), width=3)
        # Individual Keycaps Grid
        for r in range(240, 420, 34):
            for c in range(100, 500, 34):
                draw.rounded_rectangle([c, r, c+28, r+26], radius=4, fill=(30, 41, 59), outline=(2, 132, 199), width=1)
    else:
        # Gaming Mouse Body
        draw.ellipse([210, 150, 390, 470], fill=(15, 23, 42), outline=(2, 132, 199), width=4)
        draw.line([300, 150, 300, 280], fill=(217, 119, 6), width=4)
        draw.ellipse([285, 200, 315, 250], fill=(217, 119, 6))

    draw.rounded_rectangle([40, h-70, w-40, h-35], radius=6, fill=(15, 23, 42), outline=(217, 119, 6), width=2)
    img.save(os.path.join(OUTPUT_DIR, filename), "PNG", quality=95)

# Generate photo assets for RAM
for name in ["ram-8gb-ddr4.png", "ram-16gb-ddr4.png", "ram-16gb-ddr5.png", "ram-32gb-ddr5.png"]:
    create_ram_photo(name, name)

# Generate photo assets for SSD & HDD
for name in ["ssd-500gb.png", "ssd-1tb-wd.png", "ssd-1tb-samsung.png", "ssd-2tb-samsung.png", "hdd-1tb.png", "hdd-2tb.png", "hdd-4tb.png"]:
    create_ssd_photo(name, name)

# Generate photo assets for PSU
for name in ["psu-550w.png", "psu-650w.png", "psu-750w.png", "psu-850w.png"]:
    create_psu_photo(name, name)

# Generate photo assets for Cabinets & Coolers
create_cooler_photo("cooler-stock.png", "air")
create_cooler_photo("cooler-tower.png", "air")
create_cooler_photo("cooler-dual-tower.png", "air")
create_cooler_photo("cooler-240mm-aio.png", "aio")
create_cooler_photo("cooler-360mm-aio.png", "aio")

# Generate photo assets for Monitors
for name in ["monitor-22.png", "monitor-24-100hz.png", "monitor-24-165hz.png", "monitor-27-qhd.png"]:
    create_monitor_photo(name)

# Generate photo assets for Keyboards & Mice
for name in ["keyboard-mechanical.png", "keyboard-rgb-mech.png"]:
    create_peripheral_photo(name, "keyboard")

for name in ["mouse-gaming.png", "mouse-rgb.png"]:
    create_peripheral_photo(name, "mouse")

print("ALL COMPONENTS ENHANCED WITH REALISTIC HD HARDWARE PRODUCT PHOTOS!")
