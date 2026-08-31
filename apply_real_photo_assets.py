import os
import shutil
import json
from PIL import Image, ImageDraw, ImageFont, ImageFilter

BRAIN_DIR = r"C:\Users\vijen\.gemini\antigravity-ide\brain\35d8d394-f4fd-43a6-acac-968559320614"
TARGET_DIR = os.path.join("assets", "images", "products")
os.makedirs(TARGET_DIR, exist_ok=True)

# Copy generated photos
photo_map = {
    "starter-gaming-pc.png": "real_starter_gaming_pc_1788164092968.png",
    "budget-gaming-pc.png": "real_starter_gaming_pc_1788164092968.png",
    "gaming-pro.png": "real_gaming_pro_1788164111576.png",
    "creator-pc.png": "real_creator_pc_1788164128908.png",
    "editing-beast.png": "real_creator_pc_1788164128908.png",
    "ultimate-gaming-pc.png": "real_ultimate_gaming_pc_1788164264233.png",
    "student-pc.png": "real_gaming_pro_1788164111576.png",
    "office-pc.png": "real_creator_pc_1788164128908.png",
    
    # Components
    "gpu-rtx-3050.png": "real_gpu_graphics_card_1788164283492.png",
    "gpu-rtx-4060.png": "real_gpu_graphics_card_1788164283492.png",
    "gpu-rtx-4060ti.png": "real_gpu_graphics_card_1788164283492.png",
    "gpu-rtx-4070.png": "real_gpu_graphics_card_1788164283492.png",
    "gpu-rx-6600.png": "real_gpu_graphics_card_1788164283492.png",
    
    "cpu-ryzen5-5600.png": "real_cpu_processor_1788164302252.png",
    "cpu-ryzen5-7600.png": "real_cpu_processor_1788164302252.png",
    "cpu-intel-i5-14400f.png": "real_cpu_processor_1788164302252.png",
    "cpu-intel-i7-14700k.png": "real_cpu_processor_1788164302252.png",
    "cpu-ryzen7-7800x3d.png": "real_cpu_processor_1788164302252.png",
    
    "mb-b550.png": "real_motherboard_1788164326152.png",
    "mb-b650.png": "real_motherboard_1788164326152.png",
    "mb-b760-gigabyte.png": "real_motherboard_1788164326152.png",
    "mb-b760-asus.png": "real_motherboard_1788164326152.png"
}

for dest_name, src_name in photo_map.items():
    src_path = os.path.join(BRAIN_DIR, src_name)
    dest_path = os.path.join(TARGET_DIR, dest_name)
    if os.path.exists(src_path):
        shutil.copy(src_path, dest_path)
        print(f"Copied studio photo -> {dest_path}")

print("Applied photographic studio assets for PCs, GPUs, CPUs, and Motherboards!")
