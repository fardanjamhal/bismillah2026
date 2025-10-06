<!--
<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Login</title>
    </Head>

    <div class="d-flex align-items-center justify-content-center py-5"
        :class="{ 'has-background': $page.props.setting.authentication_background }"
        :style="{ backgroundImage: $page.props.setting.authentication_background ? `url('/storage/upload_files/settings/${$page.props.setting.authentication_background}')` : '' }">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="card mb-0">
                        <div class="card-body" :style="{ background: $page.props.setting.login_page_background }">
                            <div class="p-3">
                                <div class="text-center">
                                    <img v-if="$page.props.setting && $page.props.setting.logo" v-bind:src="'/storage/upload_files/settings/' + $page.props.setting.logo" style="max-width: 100%; height: 100px;"/>
                                </div>
                                <div class="mb-3">
                                    <h5 class="">Login</h5>
                                    <p class="mb-0">Silakan login dengan email dan password yang sudah anda daftarkan.</p>
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
                                            <label class="form-label">{{ loginTitle  }}</label>
                                            <input type="text" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control" :placeholder="loginPlaceholder">
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</label>
                                            <div class="input-group">
                                                <input v-if="showPassword" type="text" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <input v-else type="password" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">

                                                <span class="input-group-text" @click="toggleShow"><i :class="{ 'bx bx-show': showPassword, 'bx bx-hide': !showPassword }"></i></span>
                                                <div v-if="errors.password" class="invalid-feedback">
                                                    {{ errors.password }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">Login</button>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-center ">
                                                <div v-if="$page.props.setting.add_user_registration == 1">Belum punya akun ? <Link href="/register">Daftar Disini</Link><br><br></div>
                                                Lupa {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }} ? <Link href="/user/forgot-password">Klik Disini</Link><br><br>
                                                <a :href="`https://wa.me/${$page.props.setting.whatsapp_number}?text=${encodeURI('Hallo, Admin. saya ingin bertanya....')}`" target="_blank">Klik Untuk Menghubungi Admin</a>
                                            </div>
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

<style>
    .justify-content-center {
        justify-content: center;
        align-items: center;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        height: auto;
    }

    .justify-content-center.has-background::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5));
    }
</style>

<script>
    //import layout
    import LayoutAuth from '../../Layouts/Auth.vue';

    // import Head form Inertia
    import { Head } from '@inertiajs/inertia-vue3';

    //import reactive
    import { reactive } from 'vue';

    import { ref } from 'vue';

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
            session: Object
        },

        setup() {
            const form = reactive({
                email: '',
                password: ''
            });

            const showPassword = ref(false);
            
            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/login', {
                    // data
                    email: form.email,
                    password: form.password,
                });
            }

            const toggleShow = () => {
                showPassword.value = !showPassword.value; // Update the value using .value with ref
            };

            // return form state and submit method
            return {
                form,
                showPassword,
                toggleShow, 
                submit,
            }
        },
        computed: {
            loginTitle() {
                const loginTypes = this.$page.props.setting.login_type;
                const authenticationField = this.$page.props.setting.authentication_field;

                const getTranslation = (type) => {
                    const field = authenticationField.find(field => field.name === type);
                    return field ? field.translate : type; // Jika tidak ditemukan, gunakan nama `type`
                };

                if (loginTypes.length === 3) {
                    return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)} / ${getTranslation(loginTypes[2].type)}`;
                } else if (loginTypes.length === 2) {
                    return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)}`;
                } else if (loginTypes.length === 1) {
                    return getTranslation(loginTypes[0].type);
                }
                return "";
            },
            loginPlaceholder() {
                const loginTypes = this.$page.props.setting.login_type;
                const authenticationField = this.$page.props.setting.authentication_field;

                const getTranslation = (type) => {
                    const field = authenticationField.find(field => field.name === type);
                    return field ? field.translate : type; 
                };

                if (loginTypes.length === 3) {
                    return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)} / ${getTranslation(loginTypes[2].type)}`;
                } else if (loginTypes.length === 2) {
                    return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)}`;
                } else if (loginTypes.length === 1) {
                    return getTranslation(loginTypes[0].type);
                }
                return "Login";
            }
        }
    }
</script>
-->

<template>
  <Head>
    <title>{{ $page?.props?.setting?.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Login</title>
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
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
          <div class="card login-card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
              <div class="text-center mb-4">
                <img
                  v-if="$page?.props?.setting?.logo"
                  :src="'/storage/upload_files/settings/' + $page.props.setting.logo"
                  alt="Logo"
                  class="logo"
                />
                <h1 class="h5 fw-semibold mt-3 mb-1">Masuk Akun</h1>
                <p class="text-muted mb-0 small">Masuk dan mulai belajar bersama kami!</p>
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
                <!-- Email -->
                <div class="mb-2" style="margin-bottom: 0.6rem !important;">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.25rem;">{{ loginTitle }}</label>
                <input
                    type="text"
                    v-model="form.email"
                    :class="['form-control', { 'is-invalid': !!errors.email }]"
                    :placeholder="loginPlaceholder"
                    autocomplete="username"
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
                <div v-if="errors.email" class="invalid-feedback" style="font-size: 0.8rem; margin-top: 2px;">
                    {{ errors.email }}
                </div>
                </div>

                <!-- Password -->
                <div class="mb-2" style="margin-bottom: 0.5rem !important;">
                <label class="form-label" style="font-size: 0.9rem; margin-bottom: 0.25rem;">{{ passwordLabel }}</label>
                <div class="position-relative">
                    <input
                    :type="showPassword ? 'text' : 'password'"
                    v-model="form.password"
                    :class="['form-control', { 'is-invalid': !!errors.password }]"
                    :placeholder="passwordLabel"
                    autocomplete="current-password"
                    style="
                        border-radius: 10px;
                        padding: 8px 38px 8px 12px;
                        font-size: 0.9rem;
                        height: 38px;
                        line-height: 1.3;
                        border: 1px solid #d1d5db;
                        transition: all 0.2s ease;
                    "
                    onfocus="this.style.border='1px solid #2563eb'; this.style.boxShadow='0 0 0 2px rgba(37,99,235,0.15)'"
                    onblur="this.style.border='1px solid #d1d5db'; this.style.boxShadow='none'"
                    />
                    <button
                    type="button"
                    class="toggle"
                    @click="toggleShow"
                    :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                    style="
                        position: absolute;
                        right: 10px;
                        top: 50%;
                        transform: translateY(-50%);
                        border: none;
                        background: transparent;
                        color: #64748b;
                        font-size: 1.1rem;
                        cursor: pointer;
                        padding: 2px;
                    "
                    onmouseover="this.style.color='#2563eb'"
                    onmouseout="this.style.color='#64748b'"
                    >
                    <i :class="showPassword ? 'bx bx-show' : 'bx bx-hide'"></i>
                    </button>
                    <div v-if="errors.password" class="invalid-feedback" style="font-size: 0.8rem; margin-top: 2px;">
                    {{ errors.password }}
                    </div>
                </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" v-model="form.remember" />
                    <label class="form-check-label" for="remember">Ingat saya</label>
                  </div>
                  <Link href="/user/forgot-password" class="small link-accent text-decoration-none">Lupa Password?</Link>
                </div>

                <button
                type="submit"
                class="w-100"
                style="
                    background: linear-gradient(90deg, #2563eb, #1d4ed8);
                    color: #ffffff;
                    font-weight: 600;
                    font-size: 1.1rem;
                    letter-spacing: 0.3px;
                    border: none;
                    border-radius: 12px;
                    padding: 12px 0;
                    cursor: pointer;
                    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
                    transition: all 0.3s ease;
                "
                onmouseover="this.style.background='linear-gradient(90deg, #1e40af, #2563eb)'; this.style.boxShadow='0 6px 20px rgba(37,99,235,0.45)'; this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='linear-gradient(90deg, #2563eb, #1d4ed8)'; this.style.boxShadow='0 4px 15px rgba(37,99,235,0.3)'; this.style.transform='translateY(0)'"
                onmousedown="this.style.transform='scale(0.98)'; this.style.boxShadow='0 3px 10px rgba(37,99,235,0.25)'"
                onmouseup="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(37,99,235,0.45)'"
                >
                Masuk
                </button>


                <div class="text-center mt-3 small">
                  <div v-if="$page?.props?.setting?.add_user_registration == 1">
                    Belum punya akun? <Link href="/register" class="link-accent fw-semibold">Daftar sekarang</Link>
                  </div>
                  <a
                    class="btn btn-outline-secondary btn-sm rounded-pill mt-2"
                    :href="`https://wa.me/${$page?.props?.setting?.whatsapp_number || ''}?text=${encodeURI('Hallo, Admin. saya ingin bertanya....')}`"
                    target="_blank"
                    rel="noopener"
                  >Hubungi Admin</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dim layer when bg image exists -->
    <div v-if="$page?.props?.setting?.authentication_background" class="dim" aria-hidden="true"></div>
  </div>
</template>

<script>
import LayoutAuth from '../../Layouts/Auth.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default {
  layout: LayoutAuth,
  components: { Head, Link },
  props: {
    errors: { type: Object, default: () => ({}) },
    session: { type: Object, default: () => ({}) }
  },
  setup () {
    const form = reactive({ email: '', password: '', remember: false })
    const showPassword = ref(false)

    const submit = () => {
      Inertia.post('/login', {
        email: form.email,
        password: form.password,
        remember: form.remember
      })
    }

    const toggleShow = () => { showPassword.value = !showPassword.value }

    return { form, showPassword, toggleShow, submit }
  },
  computed: {
    safeSetting () {
      const s = this.$page?.props?.setting || {}
      return {
        login_type: Array.isArray(s.login_type) ? s.login_type : [],
        authentication_field: Array.isArray(s.authentication_field) ? s.authentication_field : []
      }
    },
    passwordLabel () {
      const field = this.safeSetting.authentication_field.find(f => f.name === 'password')
      return field?.translate || 'Password'
    },
    loginTitle () {
      const loginTypes = this.safeSetting.login_type
      const fields = this.safeSetting.authentication_field
      const getTranslation = (type) => fields.find(f => f.name === type)?.translate || type
      if (loginTypes.length === 3) return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)} / ${getTranslation(loginTypes[2].type)}`
      if (loginTypes.length === 2) return `${getTranslation(loginTypes[0].type)} / ${getTranslation(loginTypes[1].type)}`
      if (loginTypes.length === 1) return getTranslation(loginTypes[0].type)
      return 'Email / Nomor HP'
    },
    loginPlaceholder () { return this.loginTitle }
  }
}
</script>

<style scoped>
/* Base */
:root { --ink: #0f172a; --paper: #ffffff; --bg: #f5f7fb; --primary: #2563eb; --primary-600: #1d4ed8; }
.wrap { position: relative; min-height: 100vh; background: var(--bg); background-size: cover; background-position: center; }
.wrap.has-bg { background-color: #0b1220; }
.dim { position: fixed; inset: 0; background: rgba(0,0,0,.45); pointer-events: none; }

/* Card */
.login-card { border-radius: 16px; background: var(--paper); }
.logo { max-width: 200px; height: 56px; object-fit: contain; }

/* Inputs & buttons */
.form-control-lg { border-radius: 12px; }
.form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 .2rem rgba(37,99,235,.15); }
.toggle { position: absolute; right: .75rem; top: 50%; transform: translateY(-50%); border: 0; background: transparent; padding: .25rem; color: #64748b; }
.toggle:hover { color: var(--primary); }
.btn-primary { background: var(--primary); border-color: var(--primary); border-radius: 12px; }
.btn-primary:hover { background: var(--primary-600); border-color: var(--primary-600); }
.link-accent { color: var(--primary); }
.link-accent:hover { color: var(--primary-600); }

/* Text */
.card-body { color: var(--ink); }
</style>
