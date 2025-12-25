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
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

  <div>
    <label class="block font-semibold mb-1">ชื่อ</label>
    <input type="text" name="firstname" required class="border p-2 w-full rounded" placeholder="Firstname">
  </div>

  <div>
    <label class="block font-semibold mb-1">สกุล</label>
    <input type="text" name="lastname" required class="border p-2 w-full rounded" placeholder="Lastname">
  </div>

  <!-- ระดับชั้น -->
  <div class="mb-4">
    <label class="block font-semibold mb-1">เพศ</label>
    <div class="flex flex-wrap gap-4">
      <label><input type="radio" name="sex" value="ชาย" required class="mr-2"> ชาย</label>
      <label><input type="radio" name="sex" value="หญิง" class="mr-2"> หญิง</label>
    </div>
  </div>

  <div class="mb-4">
    <label class="block font-semibold mb-1">ระดับชั้น</label>
    <div class="flex flex-wrap gap-4">
      <label><input type="radio" name="student_level" value="ประถมศึกษา" required class="mr-2"> ประถมศึกษา</label>
      <label><input type="radio" name="student_level" value="มัธยมศึกษาตอนต้น" class="mr-2"> มัธยมศึกษาตอนต้น</label>
      <label><input type="radio" name="student_level" value="มัธยมศึกษาตอนปลาย" class="mr-2"> มัธยมศึกษาตอนปลาย</label>
    </div>
  </div>
</div>