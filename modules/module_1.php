<!-- 🔹 ศกร./ตำบล -->
<div class="mb-4">
  <label class="block font-semibold mb-1">ศกร./ตำบล</label>
  <input type="text" name="school" required class="border p-2 w-full rounded" placeholder="school">
</div>

<!-- 🔹 กล้องวงจรปิด (CCTV) -->
<div class="mb-4">
    <label class="block font-semibold mb-1">กล้องวงจรปิด (CCTV)</label>
    <div class="flex flex-col gap-2">
        
        <label>
            <input type="radio" name="cctv_status" value="มี" id="cctv_status_has" required class="mr-2" 
                   onclick="toggleCCTVAmount(true)"> 
            มี 
        </label>
        
        <div id="cctv_amount_container" class="ml-6 flex items-center hidden">
            <input type="number" name="cctv_amount" id="cctv_amount" value="0" min="0" 
                   class="border p-1 rounded w-20 text-center" disabled>
            <span class="ml-2">ตัว</span>
        </div>
        
        <label>
            <input type="radio" name="cctv_status" value="ไม่มี" id="cctv_status_none" class="mr-2" 
                   onclick="toggleCCTVAmount(false)"> 
            ไม่มี
        </label>
    </div>
</div>

<!-- 🔹 ตู้แดง -->
<div class="mb-4">
  <label class="block font-semibold mb-1">ตู้แดง</label>
  <div class="flex flex-wrap gap-6">
    <label><input type="radio" name="red_box_status" value="มี" required class="mr-2"> มี</label>
    <label><input type="radio" name="red_box_status" value="ไม่มีและต้องการติดตั้ง" class="mr-2"> ไม่มีและต้องการติดตั้ง</label>
    <label><input type="radio" name="red_box_status" value="ไม่มีและไม่ต้องการติดตั้ง" class="mr-2"> ไม่มีและไม่ต้องการติดตั้ง</label>
  </div>
</div>

<!-- 🔹 ชื่อ-สกุล ผู้รายงาน ครู ศกร.ตำบล -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
  <div>
    <label class="block font-semibold mb-1">ชื่อ-สกุล ผู้รายงาน</label>
    <input type="text" name="reporter_name" required class="border p-2 w-full rounded">
  </div>
  <div>
    <label class="block font-semibold mb-1">หมายเลขโทรศัพท์</label>
    <input type="tel" name="phone" required pattern="[0-9]{9,10}" placeholder="เช่น 0812345678" class="border p-2 w-full rounded">
  </div>
</div>


