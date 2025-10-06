<template>
  <Head>
    <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tryout</title>
  </Head>

  <!--start page wrapper -->
  <div class="page-wrapper">
    <div class="page-content">

      <!-- Breadcrumb modern -->
      <div class="breadcrumb-wrap">
        <div class="left">
          <span class="title">Tryout</span>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="/user/dashboard" class="home" aria-label="Dashboard">
                  <i class="bx bx-home-alt"></i>
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Soal Tryout</li>
            </ol>
          </nav>
        </div>
        <div class="right">
          <Link :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories`" class="btn btn-primary btn-sm back-btn">
            <i class="bx bx-left-arrow-alt"></i> Kembali
          </Link>
        </div>
      </div>

      <hr class="divider"/>

      <!-- Filter/Search bar -->
      <div class="card search-card">
        <div class="card-body p-3">
          <div class="input-group search-stack">
            <span class="input-icon">
              <i class="bx bx-search"></i>
            </span>
            <input
              type="text"
              v-model="form.search"
              class="form-control form-control-sm search-input"
              placeholder="Cari judul tryout…"
            />

            <!-- (opsional) filter kategori yang kamu sematkan sebelumnya bisa diaktifkan lagi di sini -->

            <button @click.prevent="handleSearch" class="btn btn-primary btn-sm search-btn" title="Filter">
              <i class="bx bx-filter-alt"></i>
            </button>
            <Link
              :href="`/user/exam-groups/${lessonCategory.category_id}/lesson-categories/${lessonCategory.id}/exams`"
              class="btn btn-outline-secondary btn-sm reset-btn"
              title="Reset"
            >
              <i class="bx bx-refresh"></i>
            </Link>
          </div>
        </div>
      </div>

      <!-- Grid Tryout -->
      <div class="card list-shell">
        <div class="card-body">
          <div class="row g-3 g-md-4">
            <!-- Tile Tryout -->
            <div
              v-for="(exam, index) in examGroups.data"
              :key="index"
              class="col-12 col-md-6 col-lg-4 col-xxl-3"
            >
              <div class="exam-tile">
                <div class="tile-head">
                  <span class="pill">Tryout</span>
                </div>

                <h6 class="tile-title clamp-2">{{ exam.title }}</h6>

                <div class="tile-meta">
                  <div class="meta-left">
                    <i class="bx bx-time"></i>
                    <span>Siap dikerjakan</span>
                  </div>

                  <div class="meta-right">
                    <template v-if="checkMemberCategories(exam.member_categories)">
                      <Link
                        :href="`/user/exam-groups/${lessonCategory?.category_id}/lesson-categories/${lessonCategory?.id}/exams/${exam?.id}`"
                        class="btn btn-kerjakan"
                      >
                        Kerjakan
                        <i class="bx bx-right-arrow-alt"></i>
                      </Link>
                    </template>

                    <template v-else>
                      <Link
                        :href="`/user/vouchers?category_id=${exam?.category_id}`"
                        class="btn btn-upgrade"
                      >
                        Upgrade
                        <i class="bx bx-trending-up"></i>
                      </Link>
                    </template>
                  </div>
                </div>
              </div>
            </div>

            <!-- Jika tidak ada data -->
            <div v-if="!examGroups.data.length" class="col-12">
              <div class="empty-card text-center">
                <div class="empty-icon"><i class="bx bx-book-open"></i></div>
                <h6 class="mb-1">Tryout Tidak Tersedia</h6>
                <p class="text-muted mb-0">Silakan coba kata kunci lain atau cek kembali nanti.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="row mb-3" v-if="examGroups.data.length">
        <div class="col-lg-12">
          <div class="d-flex justify-content-center">
            <Pagination :links="examGroups.links"/>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!--end page wrapper -->
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import Pagination from '../../../../Components/Pagination.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import Swal from 'sweetalert2';
import { reactive, ref } from 'vue';

export default {
  layout: LayoutUser,
  components: { Link, Head, Pagination },
  props: {
    examGroups: Object,
    lessonCategory: Object,
    userMemberCategories: Object,
    subCategories: Object,
    session: Object,
  },
  setup(props) {
    const form = reactive({
      search: ref("" || (new URL(document.location)).searchParams.get('search')),
      sub_category_id: ref((new URL(document.location)).searchParams.get('sub_category_id') || ''),
    });

    const handleSearch = () => {
      Inertia.get(`/user/exam-groups/${props.lessonCategory.category_id}/lesson-categories/${props.lessonCategory.id}/exams`, {
        search: form.search,
        sub_category_id: form.sub_category_id,
      })
    };

    if (props.session && props.session.success) {
      Swal.fire({ title: "Sukses", text: props.session.success, icon: "success" });
    }

    if (props.session && props.session.error) {
      Swal.fire({
        title: "Peringatan",
        text: props.session.error,
        icon: "error",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Top Up Saldo',
        cancelButtonText: 'Tidak'
      })
      .then((result) => {
        if (result.isConfirmed) {
          Inertia.get('/user/account-balances');
        }
      })
    }

    const checkMemberCategories = (categories) => {
      if (!categories || !Array.isArray(categories) || categories.length === 0) return true;
      const categoryIds = categories.map(category => String(category.id));
      return props.userMemberCategories.some(entry => categoryIds.includes(String(entry.member_category_id)));
    };

    return { form, handleSearch, checkMemberCategories };
  },
  methods: {
    formatPrice(value) {
      let val = (value/1).toFixed().replace('.', ',')
      return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    }
  }
}
</script>

<style>
/* ====== Breadcrumb modern ====== */
.breadcrumb-wrap{
  display:flex; align-items:center; justify-content:space-between;
  gap:1rem; flex-wrap:wrap; margin-bottom:1rem;
}
.breadcrumb-wrap .title{
  font-weight:800; letter-spacing:.2px; font-size:1.06rem;
}
.breadcrumb-wrap .left{display:flex; align-items:center; gap:14px;}
.breadcrumb .breadcrumb-item + .breadcrumb-item::before{ content:"›"; }
.breadcrumb-wrap .home{
  display:inline-flex; align-items:center; justify-content:center;
  width:34px; height:34px; border-radius:10px; background:#f1f5f9; color:#0f172a;
  transition:transform .18s ease, background .18s ease;
}
.breadcrumb-wrap .home:hover{ transform:translateY(-1px); background:#e2e8f0; }
.back-btn{
  font-weight:600; border-radius:12px;
  box-shadow:0 6px 16px rgba(2,6,23,.08);
  transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
}
.back-btn:hover{ transform:translateY(-1px); box-shadow:0 10px 24px rgba(2,6,23,.12); filter:brightness(1.02); }
.divider{ opacity:.2; margin:.25rem 0 1.25rem; }

/* ====== Search bar ====== */
.search-card{ border:1px solid #eef2f7; border-radius:16px; box-shadow:0 8px 20px rgba(2,6,23,.04); }
.search-stack{
  gap:.5rem; align-items:center; position:relative;
}
.input-icon{
  position:absolute; left:14px; z-index:2; pointer-events:none; color:#64748b;
  display:flex; align-items:center; height:32px;
}
.input-icon i{ font-size:18px; }
.search-input{
  padding-left:40px !important; border-radius:12px; border:1px solid #e5e7eb;
  height:38px;
}
.search-input:focus{
  outline:none; border-color:#93c5fd; box-shadow:0 0 0 3px rgba(59,130,246,.15);
}
.search-btn, .reset-btn{
  border-radius:10px; height:38px; display:inline-flex; align-items:center; gap:.35rem;
}
.reset-btn{ border-color:#cbd5e1; }

/* ====== List shell ====== */
.list-shell{
  border:none; border-radius:16px; background:#f8fafc;
}

/* ====== Exam tile ====== */
.exam-tile{
  height:100%;
  border:1px solid #eef2f7; border-radius:16px; background:#fff;
  padding:16px 16px 14px;
  box-shadow:0 8px 22px rgba(2,6,23,.05);
  transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
}
.exam-tile:hover{
  transform:translateY(-4px); box-shadow:0 16px 36px rgba(2,6,23,.1); border-color:#dbe3f0;
}
.tile-head{ display:flex; justify-content:space-between; align-items:center; margin-bottom:.35rem; }
.pill{
  display:inline-flex; align-items:center; height:22px; padding:0 .55rem;
  border-radius:999px; font-size:.7rem; font-weight:800; letter-spacing:.3px;
  background:#eef2ff; color:#4f46e5;
}
.tile-title{
  font-weight:800; letter-spacing:.2px; color:#0f172a;
  margin: 6px 0 10px;
}
.clamp-2{
  overflow:hidden; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; line-clamp:2;
}

.tile-meta{
  display:flex; align-items:center; justify-content:space-between; gap:.75rem; margin-top:auto;
}
.meta-left{ color:#6b7280; display:inline-flex; align-items:center; gap:.45rem; font-size:.92rem; }
.meta-left i{ font-size:1.05rem; }

/* ====== Buttons (Kerjakan / Upgrade) ====== */
.btn-kerjakan{
  --c1:#3b82f6; --c2:#2563eb;
  display:inline-flex; align-items:center; gap:.4rem;
  border:none; color:#fff; font-weight:800; letter-spacing:.2px;
  padding:.5rem .9rem; border-radius:999px; cursor:pointer; text-decoration:none;
  background: linear-gradient(135deg, var(--c1) 0%, var(--c2) 100%);
  box-shadow:0 10px 22px rgba(37,99,235,.22);
  transition: transform .15s ease, box-shadow .2s ease, filter .15s ease;
}
.btn-kerjakan:hover{
  transform:translateY(-2px); filter:brightness(1.02);
  box-shadow:0 14px 28px rgba(37,99,235,.28);
}
.btn-kerjakan i{ font-size:1.05rem; }

.btn-upgrade{
  display:inline-flex; align-items:center; gap:.4rem;
  border:1px solid #16a34a; color:#16a34a; font-weight:800; letter-spacing:.2px;
  padding:.5rem .9rem; border-radius:999px; text-decoration:none;
  background:#ffffff; transition: transform .15s ease, box-shadow .2s ease, background .2s ease, color .2s ease;
  box-shadow:0 0 0 rgba(0,0,0,0);
}
.btn-upgrade:hover{
  transform:translateY(-2px);
  background:#16a34a; color:#fff; box-shadow:0 12px 24px rgba(22,163,74,.22);
}
.btn-upgrade i{ font-size:1.05rem; }

/* ====== Empty state ====== */
.empty-card{
  border:1px dashed #e5e7eb; border-radius:16px;
  background:linear-gradient(180deg, #fff 0%, #fafbfd 100%);
  box-shadow:0 8px 20px rgba(2,6,23,.04);
  padding:28px 16px;
}
.empty-icon{
  width:64px; height:64px; margin:0 auto 12px; border-radius:16px;
  display:grid; place-items:center; background:#eef2ff; color:#4f46e5; font-size:30px;
}

/* ====== Responsif ====== */
@media (max-width: 576px){
  .search-stack{ gap:.4rem; }
  .btn-kerjakan, .btn-upgrade{ padding:.45rem .8rem; }
}
</style>
