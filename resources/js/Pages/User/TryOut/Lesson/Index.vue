<template>
  <Head>
    <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Data Mata Pelajaran</title>
  </Head>

  <!--start page wrapper -->
  <div class="page-wrapper">
    <div class="page-content">

      <!-- Breadcrumb modern -->
      <div class="breadcrumb-wrap">
        <div class="left">
          <span class="title">Latihan Soal</span>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item">
                <a href="/user/dashboard" class="home" aria-label="Dashboard">
                  <i class="bx bx-home-alt"></i>
                </a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Data Mata Pelajaran</li>
            </ol>
          </nav>
        </div>
        <div class="right">
          <Link
            :href="`/user/categories/${categoryId}/lesson-categories`"
            class="btn btn-primary btn-sm back-btn"
          >
            <i class="bx bx-left-arrow-alt"></i> Kembali
          </Link>
        </div>
      </div>

      <hr class="divider"/>

      <!-- Empty state -->
      <div v-if="!lessons || !lessons.length">
        <div class="row">
          <div class="col-12">
            <div class="card empty-card text-center">
              <div class="card-body py-5">
                <div class="empty-icon"><i class="bx bx-book-open"></i></div>
                <h5 class="mb-2 fw-semibold">Mata Pelajaran Belum Tersedia</h5>
                <p class="text-muted mb-0">Silakan kembali nanti atau hubungi admin untuk pembaruan data.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- List lessons (ikon + teks, tanpa foto) -->
      <div v-else class="row g-3 g-md-4">
        <div
          class="col-12 col-lg-6"
          v-for="lesson in lessons"
          :key="lesson.id || lesson.name"
        >
          <div class="card lesson-row h-100 p-2">
            <div class="card-body d-flex align-items-start gap-3">

              <!-- Ikon -->
              <div class="icon-box">
                <i class="bx bx-layer"></i>
              </div>

              <!-- Konten -->
              <div class="flex-grow-1">
                <div class="chips">
                  <span class="chip-main">Pelajaran</span>
                  <span class="dot">•</span>
                  <span class="chip-muted">{{ lesson?.lesson_category?.category?.name || 'Kategori' }}</span>
                </div>

                <h6 class="title clamp-1 mb-1">{{ lesson.name }}</h6>

                <p class="desc text-muted clamp-2 mb-0">
                  Kerjakan soal sesuai dengan perintah yang terdapat dalam informasi.
                </p>
              </div>

              <!-- CTA -->
              <div class="ms-auto">
                <Link
                  :href="`/user/categories/${lesson.lesson_category.category.id}/lesson-categories/${lesson.lesson_category_id}/lessons/${lesson.id}/exams`"
                  class="btn btn-outline-primary btn-sm cta"
                >
                  Selengkapnya
                  <i class="bx bx-right-arrow-alt ms-1"></i>
                </Link>
              </div>

            </div>
          </div>
        </div>
      </div>
      <!--end list-->

    </div>
  </div>
  <!--end page wrapper -->
</template>

<script>
import LayoutUser from '../../../../Layouts/Layout.vue';
import { Link, Head } from '@inertiajs/inertia-vue3';

export default {
  layout: LayoutUser,
  components: { Link, Head },
  props: {
    lessons: { type: [Array, Object], default: () => [] },
    categoryId: { type: [String, Number], default: null },
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

/* ====== Empty state ====== */
.empty-card{
  border:1px dashed #e5e7eb; border-radius:16px;
  background:linear-gradient(180deg, #fff 0%, #fafbfd 100%);
  box-shadow:0 8px 20px rgba(2,6,23,.04);
}
.empty-icon{
  width:64px; height:64px; margin:0 auto 12px; border-radius:16px;
  display:grid; place-items:center; background:#eef2ff; color:#4f46e5; font-size:30px;
}

/* ====== Card baris (tanpa foto) ====== */
.lesson-row{
  border:1px solid #eef2f7; border-radius:16px; background:#fff;
  box-shadow:0 10px 24px rgba(2,6,23,.05);
  transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
}
.lesson-row:hover{
  transform:translateY(-3px); box-shadow:0 16px 36px rgba(2,6,23,.1); border-color:#dbe3f0;
}

/* Ikon */
.icon-box{
  min-width:56px; height:56px; border-radius:14px;
  display:flex; align-items:center; justify-content:center;
  background:linear-gradient(135deg, #eff6ff, #dbeafe);
  color:#2563eb; font-size:1.5rem;
  box-shadow:0 4px 10px rgba(37,99,235,.12);
}

/* Chips kecil */
.chips{ display:flex; align-items:center; gap:.5rem; margin-bottom:.2rem; }
.chip-main{
  display:inline-flex; align-items:center; height:20px; padding:0 .55rem;
  border-radius:999px; font-size:.68rem; font-weight:800; letter-spacing:.3px;
  background:#eef2ff; color:#4f46e5;
}
.chip-muted{ color:#6b7280; font-size:.78rem; }
.dot{ color:#9ca3af; font-size:.9rem; }

/* Teks */
.title{ font-weight:800; letter-spacing:.2px; color:#0f172a; }
.desc{ font-size:.92rem; color:#64748b; }

/* CTA */
.cta{
  border-radius:12px; font-weight:800; letter-spacing:.2px; padding:.45rem .85rem;
  transition:transform .18s ease, box-shadow .18s ease, filter .18s ease;
  white-space:nowrap;
}
.cta:hover{ transform:translateY(-2px); filter:brightness(1.03); box-shadow:0 10px 24px rgba(37,99,235,.18); }

/* Clamp teks (aman untuk toolchain) */
.clamp-1, .clamp-2{
  overflow:hidden; display:-webkit-box; -webkit-box-orient:vertical;
}
.clamp-1{ -webkit-line-clamp:1; line-clamp:1; }
.clamp-2{ -webkit-line-clamp:2; line-clamp:2; }

/* Responsif */
@media (max-width: 576px){
  .icon-box{ min-width:48px; height:48px; font-size:1.35rem; border-radius:12px; }
  .cta{ padding:.42rem .8rem; }
}
</style>
