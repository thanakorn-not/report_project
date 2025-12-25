<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
  <div class="col-span-4 bg-white p-4 rounded shadow">

    <h3 class="text-lg font-bold mb-4">ชื่อ-สกุล (ผู้ไม่รู้หนังสือ)</h3>

    <!-- คำนำหน้า -->
    <div class="mb-4">
      <label class="block font-semibold mb-1">คำนำหน้า</label>
      <div class="flex flex-wrap gap-4">
        <label><input type="radio" name="student_prefix" value="เด็กชาย" required class="mr-2"> เด็กชาย</label>
        <label><input type="radio" name="student_prefix" value="เด็กหญิง" class="mr-2"> เด็กหญิง</label>
        <label><input type="radio" name="student_prefix" value="นาย" class="mr-2"> นาย</label>
        <label><input type="radio" name="student_prefix" value="นาง" class="mr-2"> นาง</label>
        <label><input type="radio" name="student_prefix" value="นางสาว" class="mr-2"> นางสาว</label>
        <label><input type="radio" name="student_prefix" value="พระ" class="mr-2"> พระ</label>
        <label><input type="radio" name="student_prefix" value="สามเณร" class="mr-2"> สามเณร</label>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

  <div>
    <label class="block font-semibold mb-1">ชื่อ</label>
    <input type="text" name="student_firstname" required class="border p-2 w-full rounded" placeholder="Firstname">
  </div>

  <div>
    <label class="block font-semibold mb-1">สกุล</label>
    <input type="text" name="student_lastname" required class="border p-2 w-full rounded" placeholder="Lastname">
  </div>

  <!-- ระดับชั้น -->
<div class="mb-4">
  <label class="block font-semibold mb-1">เพศ</label>
  <div class="flex flex-wrap gap-4">
    <label><input type="radio" name="student_gender" value="ชาย" required class="mr-2"> ชาย</label>
    <label><input type="radio" name="student_gender" value="หญิง" class="mr-2"> หญิง</label>
  </div>
</div>

</div>

  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
  <div class="col-span-4 bg-white p-4 rounded shadow">

    <h3 class="text-lg font-bold mb-4">ชื่อ-สกุล (ครูผู้สอน)</h3>

    <!-- คำนำหน้า -->
    <div class="mb-4">
      <label class="block font-semibold mb-1">คำนำหน้า</label>
      <div class="flex flex-wrap gap-4">
        <label><input type="radio" name="teacher_prefix" value="นาย" class="mr-2"> นาย</label>
        <label><input type="radio" name="teacher_prefix" value="นาง" class="mr-2"> นาง</label>
        <label><input type="radio" name="teacher_prefix" value="นางสาว" class="mr-2"> นางสาว</label>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

  <div>
    <label class="block font-semibold mb-1">ชื่อ</label>
    <input type="text" name="teacher_firstname" required class="border p-2 w-full rounded" placeholder="Firstname">
  </div>

  <div>
    <label class="block font-semibold mb-1">สกุล</label>
    <input type="text" name="teacher_lastname" required class="border p-2 w-full rounded" placeholder="Lastname">
  </div>


</div>

  </div>
</div>

<div class="mb-4">
      <label class="block font-semibold mb-1">ผลการประเมิน</label>
      <div class="flex flex-wrap gap-4">
        <label><input type="radio" name="result" value="ผ่าน" class="mr-2"> ผ่าน</label>
        <label><input type="radio" name="result" value="ไม่ผ่าน" class="mr-2"> ไม่ผ่าน</label>
      </div>
    </div>