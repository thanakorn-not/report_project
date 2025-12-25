<!-- 🔹 คำนำหน้า -->
<div class="mb-4">
  <label class="block font-semibold mb-1">คำนำหน้า</label>
  <div class="flex flex-wrap gap-4">
    <label><input type="radio" name="prefix" value="นาย" class="mr-2"> นาย</label>
    <label><input type="radio" name="prefix" value="นาง" class="mr-2"> นาง</label>
    <label><input type="radio" name="prefix" value="นางสาว" class="mr-2"> นางสาว</label>
    <label><input type="radio" name="prefix" value="ว่าที่ร้อยตรี" class="mr-2"> ว่าที่ร้อยตรี</label>
    <label><input type="radio" name="prefix" value="ว่าที่ร้อยตรีหญิง" class="mr-2"> ว่าที่ร้อยตรีหญิง</label>
  </div>
</div>

<!-- 🔹 ชื่อ - สกุล -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
  <div>
    <label class="block font-semibold mb-1">ชื่อ</label>
    <input type="text" name="firstname" required class="border p-2 w-full rounded" placeholder="Firstname">
  </div>
  <div>
    <label class="block font-semibold mb-1">สกุล</label>
    <input type="text" name="lastname" required class="border p-2 w-full rounded" placeholder="Lastname">
  </div>
</div>

<!-- 🔹 ตำแหน่ง -->
<div class="mb-4">
  <label class="block font-semibold mb-1">ตำแหน่ง</label>
  <input type="text" name="position" required class="border p-2 w-full rounded" placeholder="position">
</div>

<!-- 🔹 วุฒิทางลูกเสือ -->
<div class="mb-4">
  <label class="block font-semibold mb-1">มีวุฒิทางลูกเสือ</label>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
    <label><input type="radio" name="scout_qualification" value="B.T.C.สำรอง" class="mr-2"> B.T.C.สำรอง</label>
    <label><input type="radio" name="scout_qualification" value="B.T.C.สามัญ" class="mr-2"> B.T.C.สามัญ</label>
    <label><input type="radio" name="scout_qualification" value="B.T.C.สามัญรุ่นใหญ่" class="mr-2"> B.T.C.สามัญรุ่นใหญ่</label>
    <label><input type="radio" name="scout_qualification" value="B.T.C.วิสามัญ" class="mr-2"> B.T.C.วิสามัญ</label>
    <label><input type="radio" name="scout_qualification" value="A.T.C.สำรอง" class="mr-2"> A.T.C.สำรอง</label>
    <label><input type="radio" name="scout_qualification" value="A.T.C.สามัญ" class="mr-2"> A.T.C.สามัญ</label>
    <label><input type="radio" name="scout_qualification" value="A.T.C.สามัญรุ่นใหญ่" class="mr-2"> A.T.C.สามัญรุ่นใหญ่</label>
    <label><input type="radio" name="scout_qualification" value="A.T.C.วิสามัญ" class="mr-2"> A.T.C.วิสามัญ</label>
    <label><input type="radio" name="scout_qualification" value="W.B." class="mr-2"> W.B.</label>
    <label><input type="radio" name="scout_qualification" value="A.L.T.C." class="mr-2"> A.L.T.C.</label>
    <label><input type="radio" name="scout_qualification" value="A.L.T." class="mr-2"> A.L.T.</label>
    <label><input type="radio" name="scout_qualification" value="L.T.C." class="mr-2"> L.T.C.</label>
    <label><input type="radio" name="scout_qualification" value="L.T." class="mr-2"> L.T.</label>
  </div>

  <!-- ไม่มีวุฒิ -->
  <div class="mt-2">
    <label><input type="radio" name="scout_qualification" value="ไม่มีวุฒิ" class="mr-2"> ไม่มีวุฒิ</label>
  </div>
</div>

<!-- 🔹 วันเดือนปีที่ได้รับการอบรม -->
<div class="mb-4">
  <label class="block font-semibold mb-1">วันเดือนปีที่ได้รับการอบรม</label>
  <input type="date" name="training_date" required class="border p-2 w-full rounded" >
</div>

<!-- 🔹 ความสามารถพิเศษ -->
<div class="mb-4">
  <label class="block font-semibold mb-1">ความสามารถพิเศษ</label>
  <input type="text" name="ability" class="border p-2 w-full rounded" placeholder="ability">
</div>
