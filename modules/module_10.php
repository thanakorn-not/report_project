<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
  <div class="col-span-4 bg-white p-4 rounded shadow">

    <!-- คำนำหน้า -->
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

  <div>
    <label class="block font-semibold mb-1">รหัสนักศึกษา</label>
    <input type="text" name="student_code" required class="border p-2 w-full rounded" placeholder="Student Code">
  </div>

  <div>
    <label class="block font-semibold mb-1">ศกร.ตำบล</label>
    <input type="text" name="school" required class="border p-2 w-full rounded" placeholder="School">
  </div>

</div>

  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">

  <div class="col-span-1 lg:col-span-4 bg-white p-4 rounded shadow">
    <h3 class="text-lg font-bold mb-4">รหัส</h3>

    <div class="space-y-6">

  <!-- ประถมศึกษา -->
  <div>
    <label class="block font-semibold mb-2">ประถมศึกษา</label>
    <div class="flex gap-6">
      <label><input type="radio" name="primary_code" value="G" class="mr-2"> G</label>
      <label><input type="radio" name="primary_code" value="0" class="mr-2"> 0</label>
      <label><input type="radio" name="primary_code" value="7" class="mr-2"> 7</label>
    </div>
  </div>

  <!-- มัธยมศึกษาตอนต้น -->
  <div>
    <label class="block font-semibold mb-2">มัธยมศึกษาตอนต้น</label>
    <div class="flex gap-6">
      <label><input type="radio" name="junior_code" value="G" class="mr-2"> G</label>
      <label><input type="radio" name="junior_code" value="0" class="mr-2"> 0</label>
      <label><input type="radio" name="junior_code" value="7" class="mr-2"> 7</label>
    </div>
  </div>

  <!-- มัธยมศึกษาตอนปลาย -->
  <div>
    <label class="block font-semibold mb-2">มัธยมศึกษาตอนปลาย</label>
    <div class="flex gap-6">
      <label><input type="radio" name="senior_code" value="G" class="mr-2"> G</label>
      <label><input type="radio" name="senior_code" value="0" class="mr-2"> 0</label>
      <label><input type="radio" name="senior_code" value="7" class="mr-2"> 7</label>
    </div>
  </div>

</div>



  </div>

</div>