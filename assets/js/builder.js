// KFC Computers Interactive Custom PC Builder Engine

let selectedComponents = {};
let componentsData = [];

document.addEventListener('DOMContentLoaded', () => {
    if (window.RAW_COMPONENTS) {
        componentsData = window.RAW_COMPONENTS;
        initBuilder();
    }
});

function initBuilder() {
    renderCategoryTabs();
    renderComponents('CPU');
    updateBuildSummary();
}

const CATEGORIES = [
    'CPU', 'Motherboard', 'RAM', 'GPU', 
    'SSD', 'HDD', 'PSU', 'Cabinet', 
    'CPU Cooler', 'Monitor', 'Keyboard', 'Mouse'
];

function renderCategoryTabs() {
    const tabsContainer = document.getElementById('builder-tabs');
    if (!tabsContainer) return;

    tabsContainer.innerHTML = '';
    CATEGORIES.forEach((cat, index) => {
        const btn = document.createElement('button');
        btn.className = `tab-btn ${index === 0 ? 'active' : ''}`;
        btn.textContent = cat;
        btn.onclick = () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderComponents(cat);
        };
        tabsContainer.appendChild(btn);
    });
}

function renderComponents(category) {
    const grid = document.getElementById('builder-grid');
    if (!grid) return;

    const filtered = componentsData.filter(c => c.category === category);
    
    if (filtered.length === 0) {
        grid.innerHTML = `<div style="grid-column: 1/-1; padding: 2rem; text-align: center; color: #000000; font-weight: 700;">No components available in ${category}.</div>`;
        return;
    }

    grid.innerHTML = filtered.map(c => {
        const isSelected = selectedComponents[category] && selectedComponents[category].id === c.id;
        const formattedPrice = '₹' + Number(c.price).toLocaleString('en-IN');
        
        return `
            <div class="product-card ${isSelected ? 'selected-card' : ''}" style="${isSelected ? 'border-color: var(--primary-blue); background: #f8fafc;' : ''}">
                <div class="product-img-wrapper">
                    <img src="${c.image}" alt="${c.name}" loading="lazy">
                    <span class="badge-category">${c.category}</span>
                </div>
                <div class="product-info">
                    <div class="product-brand">${c.brand || 'KFC'}</div>
                    <h3>${c.name}</h3>
                    <div class="product-specs-summary">${c.specifications}</div>
                    <div class="product-footer">
                        <div class="product-price">${formattedPrice}</div>
                        <button class="btn ${isSelected ? 'btn-primary' : 'btn-outline'} btn-sm" onclick="toggleSelectComponent('${category}', ${c.id})">
                            ${isSelected ? '✓ SELECTED' : 'SELECT'}
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function toggleSelectComponent(category, componentId) {
    const comp = componentsData.find(c => c.id === componentId);
    if (!comp) return;

    if (selectedComponents[category] && selectedComponents[category].id === componentId) {
        delete selectedComponents[category];
    } else {
        selectedComponents[category] = comp;
    }

    renderComponents(category);
    updateBuildSummary();
}

function updateBuildSummary() {
    const listEl = document.getElementById('build-summary-list');
    const totalEl = document.getElementById('build-total-price');
    const compatEl = document.getElementById('compatibility-status');
    const confirmBtn = document.getElementById('confirm-build-btn');

    if (!listEl || !totalEl) return;

    let total = 0;
    listEl.innerHTML = '';

    CATEGORIES.forEach(cat => {
        const item = selectedComponents[cat];
        const li = document.createElement('li');
        li.className = 'build-item';

        if (item) {
            total += Number(item.price);
            li.innerHTML = `
                <div>
                    <div class="build-item-name">${item.name}</div>
                    <div class="build-item-category">${cat}</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-weight: 800; color: var(--accent-gold);">₹${Number(item.price).toLocaleString('en-IN')}</div>
                    <a href="javascript:void(0)" onclick="toggleSelectComponent('${cat}', ${item.id})" style="color: var(--accent-red); font-size: 0.78rem; font-weight: 700;">Remove</a>
                </div>
            `;
        } else {
            li.innerHTML = `
                <div>
                    <div class="build-item-name" style="color: #64748b; font-style: italic; font-weight: 600;">Not Selected</div>
                    <div class="build-item-category">${cat}</div>
                </div>
                <div style="color: #64748b; font-size: 0.8rem; font-weight: 700;">-</div>
            `;
        }
        listEl.appendChild(li);
    });

    totalEl.textContent = '₹' + total.toLocaleString('en-IN');

    // Run Compatibility Rules
    const warnings = checkCompatibility();
    if (warnings.length === 0) {
        const count = Object.keys(selectedComponents).length;
        if (count === 0) {
            compatEl.className = 'compatibility-box';
            compatEl.innerHTML = 'Select components to build your custom PC.';
        } else {
            compatEl.className = 'compatibility-box ok';
            compatEl.innerHTML = '✓ All selected components are 100% compatible!';
        }
    } else {
        compatEl.className = 'compatibility-box';
        compatEl.innerHTML = '⚠️ <strong>Compatibility Warnings:</strong><br>' + warnings.join('<br>');
    }

    if (confirmBtn) {
        confirmBtn.disabled = Object.keys(selectedComponents).length === 0;
    }
}

function checkCompatibility() {
    const warnings = [];
    const cpu = selectedComponents['CPU'];
    const mb = selectedComponents['Motherboard'];
    const ram = selectedComponents['RAM'];
    const gpu = selectedComponents['GPU'];
    const psu = selectedComponents['PSU'];

    // 1. CPU vs Motherboard Socket
    if (cpu && mb && cpu.compatibility && mb.compatibility) {
        if (cpu.compatibility.socket && mb.compatibility.socket && cpu.compatibility.socket !== mb.compatibility.socket) {
            warnings.push(`CPU socket (${cpu.compatibility.socket}) does not match Motherboard socket (${mb.compatibility.socket}).`);
        }
    }

    // 2. Motherboard vs RAM Type (DDR4 / DDR5)
    if (mb && ram && mb.compatibility && ram.compatibility) {
        if (mb.compatibility.ram_type && ram.compatibility.ram_type && mb.compatibility.ram_type !== ram.compatibility.ram_type) {
            warnings.push(`Motherboard supports ${mb.compatibility.ram_type}, but selected RAM is ${ram.compatibility.ram_type}.`);
        }
    }

    // 3. Power Consumption vs PSU Wattage
    if (psu && psu.compatibility && psu.compatibility.psu_wattage) {
        let estimatedWattage = 100; // Base system wattage
        if (cpu && cpu.compatibility && cpu.compatibility.wattage) estimatedWattage += cpu.compatibility.wattage;
        if (gpu && gpu.compatibility && gpu.compatibility.wattage) estimatedWattage += gpu.compatibility.wattage;

        const recommendedPsu = Math.ceil((estimatedWattage + 100) / 50) * 50; // Safety headroom
        if (psu.compatibility.psu_wattage < recommendedPsu) {
            warnings.push(`Selected PSU (${psu.compatibility.psu_wattage}W) may be insufficient for system draw (Recommended: ${recommendedPsu}W+).`);
        }
    }

    return warnings;
}

function confirmCustomBuild() {
    const selectedList = Object.values(selectedComponents);
    if (selectedList.length === 0) return;

    let buildPrice = selectedList.reduce((sum, item) => sum + Number(item.price), 0);
    const customBuildItem = {
        id: 999000 + Date.now(),
        name: 'Custom KFC Gaming Build',
        category: 'Custom PC',
        price: buildPrice,
        image: 'assets/images/products/ultimate-gaming-pc.png',
        short_description: selectedList.map(i => `${i.category}: ${i.name}`).join(' | '),
        specifications: selectedList.map(i => `${i.category}: ${i.name} (₹${Number(i.price).toLocaleString('en-IN')})`).join('\n'),
        qty: 1
    };

    addToCart(customBuildItem);
    window.location.href = 'cart.php';
}
