<!--
<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Forgot {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</title>
    </Head>
    
    <div class="authentication-reset-password d-flex align-items-center justify-content-center"
        :class="{ 'has-background': $page.props.setting.authentication_background }"
        :style="{ backgroundImage: $page.props.setting.authentication_background ? `url('/storage/upload_files/settings/${$page.props.setting.authentication_background}')` : '' }">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="card mb-0">
                        <div class="card-body" :style="{ background: $page.props.setting.login_page_background }">
                            <div class="p-3">
                                <div class="mb-3">
                                    <h3 class="text-center">Reset {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</h3>
                                    <p class="text-center">Kembali Ke Halaman <Link href="/login">Login</Link></p>
                                    <p class="mt-3">Pastikan kontak yang dimasukan adalah kontak yang telah didaftarkan.</p>
                                </div>
                                <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                                    <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                                </div>
                                <div v-if="$page.props.session.warning" class="alert alert-warning border-0">
                                    <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.warning"></div>
                                </div>
                                <div v-if="$page.props.session.success" class="alert alert-success border-0">
                                    <i class="fa fa-exclamation-triangle"></i> <div v-html="$page.props.session.success"></div>
                                </div>
                                <div class="form-body">
                                    <form @submit.prevent="submit" class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">
                                                {{ $page.props.setting.authentication_field.find(field => field.name == ($page.props.setting.notification_type == 1 ? 'phone_number' : 'email'))?.translate }}
                                            </label>
                                            <input type="text" v-model="form.contact" :class="{ 'is-invalid': errors.contact }" class="form-control" :placeholder="'Contoh: ' + ($page.props.setting.notification_type == 1 ? '6281212126043' : 'user@example.com')">
                                            <div v-if="errors.contact" class="invalid-feedback">
                                                {{ errors.contact }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Kirim Perubahan {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <p class="text-center">
                                                Kontak tidak terdaftar, 
                                                <a :href="`https://wa.me/${$page.props.setting.whatsapp_number}?text=${encodeURIComponent(`*[FORGOT PASSWORD - ${$page.props.setting.app_name}]*\n\nHallo, Admin saya sudah daftar dan ingin reset password, ketika konfirmasi kontak tidak terdaftar, berikut data saya untuk perbaikan:\n\nNama:\nEmail:\nNomor Handphone Aktif:\n\n terimakasih.`)}`" target="_blank">klik disini</a> 
                                                untuk pemulihan data.
                                            </p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            
        </div>
    </div>
</template>

<script>
    //import layout
    import LayoutAuth from '../../Layouts/Auth.vue';

    // import Head form Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    // import link
    import { Link } from '@inertiajs/inertia-vue3';

    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';

    export default {
        // layout
        layout: LayoutAuth,

        components: {
            Head,
            Link
        },

        //props
        props: {
            errors: Object,
            session: Object,
            user: Object,
        },

        setup() {
            const form = reactive({
                contact: '',
            });

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/user/forgot-password', {
                    // data
                    contact: form.contact,
                });
            }

            // return form state and submit method
            return {
                form,
                submit,
            }
        }
    }
</script>
-->

<template>
  <Head>
    <title>
      {{
        $page?.props?.setting?.app_name ?? 'Atur Setting Terlebih Dahulu'
      }} - Forgot {{
        $page?.props?.setting?.authentication_field?.find(f => f.name == 'password')?.translate || 'Password'
      }}
    </title>
  </Head>

  <div
    class="wrap d-flex align-items-center justify-content-center py-5"
    :class="{ 'has-bg': !!$page?.props?.setting?.authentication_background }"
    :style="{
      backgroundImage: $page?.props?.setting?.authentication_background
        ? `url('/storage/upload_files/settings/${$page.props.setting.authentication_background}')`
        : ''
    }"
  >
    <div class="container position-relative content-layer">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-4">
          <div class="card login-card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
              <div class="text-center mb-3">
                <h3 class="fw-semibold" style="margin-bottom:.25rem;">
                  Reset {{
                    $page?.props?.setting?.authentication_field?.find(f => f.name == 'password')?.translate || 'Password'
                  }}
                </h3>
               <p class="text-muted small" style="margin-bottom:.5rem;">
  Kembali ke 
  <Link 
    href="/login" 
    class="text-decoration-none" 
    style="
      color:#2563eb; 
      font-weight:600; 
      transition:color 0.2s ease;"
    onmouseover="this.style.color='#1d4ed8'"
    onmouseout="this.style.color='#2563eb'"
  >
    halaman Login
  </Link>
</p>

                <p class="small" style="margin-bottom:0;">
                  Pastikan kontak yang dimasukkan adalah kontak yang telah didaftarkan.
                </p>
              </div>

              <!-- Alerts -->
              <div v-if="$page?.props?.session?.error" class="alert alert-danger small" role="alert">
                <div v-html="$page.props.session.error"></div>
              </div>
              <div v-if="$page?.props?.session?.warning" class="alert alert-warning small" role="alert">
                <div v-html="$page.props.session.warning"></div>
              </div>
              <div v-if="$page?.props?.session?.success" class="alert alert-success small" role="alert">
                <div v-html="$page.props.session.success"></div>
              </div>

              <form @submit.prevent="submit" novalidate>
                <!-- Kontak -->
                <div class="mb-2" style="margin-bottom:.6rem !important;">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{
                      $page?.props?.setting?.authentication_field?.find(f =>
                        f.name == ($page?.props?.setting?.notification_type == 1 ? 'phone_number' : 'email')
                      )?.translate || ($page?.props?.setting?.notification_type == 1 ? 'Nomor Handphone' : 'Email')
                    }}
                  </label>
                  <input
                    type="text"
                    v-model="form.contact"
                    :class="['form-control', { 'is-invalid': !!errors.contact }]"
                    :placeholder="'Contoh: ' + ($page?.props?.setting?.notification_type == 1 ? '62812xxxxxxx' : 'user@example.com')"
                    style="
                      border-radius: 10px;
                      padding: 8px 12px;
                      font-size: 0.9rem;
                      height: 38px;
                      line-height: 1.3;
                      border: 1px solid #d1d5db;
                      transition: all 0.2s ease;
                    "
                    onfocus="this.style.border='1px solid #2563eb'; this.style.boxShadow='0 0 0 2px rgba(37,99,235,0.15)'"
                    onblur="this.style.border='1px solid #d1d5db'; this.style.boxShadow='none'"
                  />
                  <div v-if="errors.contact" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.contact }}
                  </div>
                </div>

                <!-- Submit -->
                <button
                  type="submit"
                  class="w-100"
                  style="
                    background: linear-gradient(90deg, #2563eb, #1d4ed8);
                    color: #ffffff;
                    font-weight: 600;
                    font-size: 0.8rem;
                    letter-spacing: 0.2px;
                    border: none;
                    border-radius: 12px;
                    padding: 10px 0;
                    cursor: pointer;
                    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.28);
                    transition: all 0.25s ease;
                    margin-top:.25rem;
                  "
                  onmouseover="this.style.background='linear-gradient(90deg, #1e40af, #2563eb)'; this.style.boxShadow='0 6px 20px rgba(37,99,235,0.42)'; this.style.transform='translateY(-1px)'"
                  onmouseout="this.style.background='linear-gradient(90deg, #2563eb, #1d4ed8)'; this.style.boxShadow='0 4px 15px rgba(37,99,235,0.28)'; this.style.transform='translateY(0)'"
                  onmousedown="this.style.transform='scale(0.985)'; this.style.boxShadow='0 3px 10px rgba(37,99,235,0.22)'"
                  onmouseup="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(37,99,235,0.42)'"
                >
                  Kirim Perubahan {{
                    $page?.props?.setting?.authentication_field?.find(f => f.name == 'password')?.translate || 'Password'
                  }}
                </button>

                <p class="text-center small" style="margin-top:.75rem; margin-bottom:0;">
                    Kontak tidak terdaftar?
                    <a
                        :href="`https://wa.me/${$page?.props?.setting?.whatsapp_number || ''}?text=${encodeURIComponent(`*[FORGOT PASSWORD - ${$page?.props?.setting?.app_name || 'Aplikasi'}]*\n\nHallo, Admin saya sudah daftar dan ingin reset password, ketika konfirmasi kontak tidak terdaftar, berikut data saya untuk perbaikan:\n\nNama:\nEmail:\nNomor Handphone Aktif:\n\nTerima kasih.`)}`"
                        target="_blank"
                        class="text-decoration-none"
                        style="
                        color:#2563eb;
                        font-weight:600;
                        transition:color 0.25s ease;
                        "
                        onmouseover="this.style.color='#1d4ed8'"
                        onmouseout="this.style.color='#2563eb'"
                    >
                        Klik di sini
                    </a>
                    untuk pemulihan data.
                    </p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dim layer saat pakai background image -->
    <div v-if="$page?.props?.setting?.authentication_background" class="dim" aria-hidden="true"></div>
  </div>
</template>

<script>
import LayoutAuth from '../../Layouts/Auth.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default {
  layout: LayoutAuth,
  components: { Head, Link },
  props: {
    errors: { type: Object, default: () => ({}) },
    session: { type: Object, default: () => ({}) },
    user: { type: Object, default: () => ({}) }
  },
  setup () {
    const form = reactive({ contact: '' })
    const submit = () => {
      Inertia.post('/user/forgot-password', { contact: form.contact })
    }
    return { form, submit }
  }
}
</script>

<style scoped>
/* Base & wrapper (sama dengan login modern sederhana) */
:root { --ink: #0f172a; --paper: #ffffff; --bg: #f5f7fb; --primary: #2563eb; --primary-600: #1d4ed8; }
.wrap { position: relative; min-height: 100vh; background: var(--bg); background-size: cover; background-position: center; }
.wrap.has-bg { background-color: #0b1220; }
.dim { position: fixed; inset: 0; background: rgba(0,0,0,.45); pointer-events: none; z-index: 1; }
.content-layer { position: relative; z-index: 2; }

/* Card */
.login-card { border-radius: 16px; background: var(--paper); }
.card-body { color: var(--ink); }

/* Link accent */
a, .link { color: var(--primary); }
a:hover { color: var(--primary-600); }
</style>

