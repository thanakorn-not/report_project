<!-- 🔹 คำนำหน้า -->
<div class="mb-4">
  <label class="block font-semibold mb-1">คำนำหน้า</label>
  <div class="flex flex-wrap gap-4">
    <label><input type="radio" name="prefix" value="เด็กชาย" required class="mr-2"> เด็กชาย</label>
    <label><input type="radio" name="prefix" value="เด็กหญิง" class="mr-2"> เด็กหญิง</label>
    <label><input type="radio" name="prefix" value="นาย" class="mr-2"> นาย</label>
    <label><input type="radio" name="prefix" value="นาง" class="mr-2"> นาง</label>
    <label><input type="radio" name="prefix" value="นางสาว" class="mr-2"> นางสาว</label>
    <label><input type="radio" name="prefix" value="พระ" class="mr-2"> พระ</label>
    <label><input type="radio" name="prefix" value="สามเณร" class="mr-2"> สามเณร</label>
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

<!-- 🔹 ระดับการศึกษา -->
<div class="mb-4">
  <label class="block font-semibold mb-1">ระดับการศึกษา</label>
  <div class="flex flex-wrap gap-4">
    <label><input type="radio" name="education_level" value="ประถมศึกษา" required class="mr-2"> ประถมศึกษา</label>
    <label><input type="radio" name="education_level" value="มัธยมศึกษาตอนต้น" class="mr-2"> มัธยมศึกษาตอนต้น</label>
    <label><input type="radio" name="education_level" value="มัธยมศึกษาตอนปลาย" class="mr-2"> มัธยมศึกษาตอนปลาย</label>
  </div>
</div>

<!-- 🔹 ศกร./ตำบล -->
<div class="mb-4">
  <label class="block font-semibold mb-1">ศกร./ตำบล</label>
  <input type="text" name="school" required class="border p-2 w-full rounded" placeholder="school">
</div>

<!-- 🔹 สถานะการทำงาน -->
<div class="mb-4">
  <label class="block font-semibold mb-1">สถานะการทำงาน</label>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
    <label><input type="radio" name="employment_status" value="ว่างงาน(นักศึกษาปกติ)" required class="mr-2"> ว่างงาน(นักศึกษาปกติ)</label>
    <label><input type="radio" name="employment_status" value="ว่างงาน(ทหาร)" class="mr-2"> ว่างงาน(ทหาร)</label>
    <label><input type="radio" name="employment_status" value="ว่างงาน(ผู้ต้องขัง)" class="mr-2"> ว่างงาน(ผู้ต้องขัง)</label>
    <label><input type="radio" name="employment_status" value="ว่างงาน(พระ/สามเณร)" class="mr-2"> ว่างงาน(พระ/สามเณร)</label>
    <label><input type="radio" name="employment_status" value="ว่างงาน(สถานพินิจ)" class="mr-2"> ว่างงาน(สถานพินิจ)</label>
    <label><input type="radio" name="employment_status" value="ว่างงาน(ผู้พิการ)" class="mr-2"> ว่างงาน(ผู้พิการ)</label>
    <label><input type="radio" name="employment_status" value="มีงานทำ" class="mr-2"> มีงานทำ</label>
  </div>
</div>

<!-- 🔹 งานที่ทำ -->
<div class="mb-4">
  <label class="block font-semibold mb-1">งานที่ทำ</label>
  <input type="text" name="job" class="border p-2 w-full rounded"placeholder="job">
</div>

<!-- 🔹 สถานที่ทำงาน -->
<div class="mb-4">
  <label class="block font-semibold mb-1">สถานที่ทำงาน</label>
  <input type="text" name="workplace" class="border p-2 w-full rounded" placeholder="job place">
</div>

<!-- 🔹 อื่นๆ -->
<div class="mb-4">
  <label class="block font-semibold mb-1">อื่นๆ</label>
  <input type="text" name="other" class="border p-2 w-full rounded" placeholder="other">
</div>
