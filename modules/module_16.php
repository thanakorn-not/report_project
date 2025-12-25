<!-- ฟอร์มข้อมูลระดับการศึกษา ประถมศึกษา มัธยมต้น มัธยมปลาย -->
<div class="p-6 space-y-10">
  <!-- ประถมศึกษา -->
  <section>
    <h2 class="text-2xl font-bold mb-4">ประถมศึกษา</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="font-semibold">จำนวนนักศึกษา (จำนวนทั้งหมด)</label>
        <input type="text" name="primary_total" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label class="font-semibold">จำนวนผู้จบการศึกษา</label>
        <input type="text" name="primary_pass" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
    </div>

    <!-- ศึกษาต่อ -->
    <h3 class="text-xl font-semibold mt-6 mb-2">ศึกษาต่อในระดับที่สูงขึ้น</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label>สายสามัญ</label>
        <input type="text" name="primary_path_academic" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>สายอาชีพ</label>
        <input type="text" name="primary_path_vocational" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>ไม่ศึกษาต่อ</label>
        <input type="text" name="primary_path_none" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
    </div>

    <h3 class="text-xl font-semibold mt-6 mb-2">ประกอบอาชีพ</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label>เกษตรกรรม</label>
        <input type="text" name="primary_job_agriculture" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>พนักงานบริษัท/พนักงานโรงงาน</label>
        <input type="text" name="primary_job_company" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>ค้าขาย</label>
        <input type="text" name="primary_job_sales" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>หัตถกรรม/เย็บปักถักร้อย</label>
        <input type="text" name="primary_job_handicraft" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>รับจ้างทั่วไป</label>
        <input type="text" name="primary_job_general" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>อื่น ๆ</label>
        <input type="text" name="primary_job_other" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>ไม่ประกอบอาชีพ</label>
        <input type="text" name="primary_job_none" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
    </div>
  </section>

  <!-- มัธยมต้น -->
  <section>
    <h2 class="text-2xl font-bold mb-4">มัธยมศึกษาตอนต้น</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="font-semibold">จำนวนนักเรียนทั้งหมด</label>
        <input type="text" name="junior_total" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label class="font-semibold">จำนวนผู้จบการศึกษา</label>
        <input type="text" name="junior_pass" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
    </div>

    <h3 class="text-xl font-semibold mt-6 mb-2">ศึกษาต่อในระดับที่สูงขึ้น</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label>สายสามัญ</label>
        <input type="text" name="junior_path_academic" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>สายอาชีพ</label>
        <input type="text" name="junior_path_vocational" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>ไม่ศึกษาต่อ</label>
        <input type="text" name="junior_path_none" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
    </div>
    <h3 class="text-xl font-semibold mt-6 mb-2">ประกอบอาชีพ</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label>เกษตรกรรม</label>
        <input type="text" name="junior_job_agriculture" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>พนักงานบริษัท/พนักงานโรงงาน</label>
        <input type="text" name="junior_job_company" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>ค้าขาย</label>
        <input type="text" name="junior_job_sales" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>หัตถกรรม/เย็บปักถักร้อย</label>
        <input type="text" name="junior_job_handicraft" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>รับจ้างทั่วไป</label>
        <input type="text" name="junior_job_general" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>อื่น ๆ</label>
        <input type="text" name="junior_job_other" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>ไม่ประกอบอาชีพ</label>
        <input type="text" name="junior_job_none" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
    </div>
  </section>

  <!-- มัธยมปลาย -->
  <section>
    <h2 class="text-2xl font-bold mb-4">มัธยมศึกษาตอนปลาย</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="font-semibold">จำนวนนักเรียนทั้งหมด</label>
        <input type="text" name="senior_total" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label class="font-semibold">จำนวนผู้จบการศึกษา</label>
        <input type="text" name="senior_pass" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
    </div>

    <h3 class="text-xl font-semibold mt-6 mb-2">ศึกษาต่อในระดับที่สูงขึ้น</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label>สายสามัญ</label>
        <input type="text" name="senior_path_academic" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>สายอาชีพ</label>
        <input type="text" name="senior_path_vocational" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>ไม่ศึกษาต่อ</label>
        <input type="text" name="senior_path_none" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
    </div>
    <h3 class="text-xl font-semibold mt-6 mb-2">ประกอบอาชีพ</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <label>เกษตรกรรม</label>
        <input type="text" name="senior_job_agriculture" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>พนักงานบริษัท/พนักงานโรงงาน</label>
        <input type="text" name="senior_job_company" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>ค้าขาย</label>
        <input type="text" name="senior_job_sales" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>หัตถกรรม/เย็บปักถักร้อย</label>
        <input type="text" name="senior_job_handicraft" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>รับจ้างทั่วไป</label>
        <input type="text" name="senior_job_general" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>อื่น ๆ</label>
        <input type="text" name="senior_job_other" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
      <div>
        <label>ไม่ประกอบอาชีพ</label>
        <input type="text" name="senior_job_none" class="w-full border p-2 rounded" placeholder="คน" />
      </div>
    </div>
  </section>
</div>