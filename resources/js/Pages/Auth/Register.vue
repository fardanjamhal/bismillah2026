<template>
  <Head>
    <title>{{ $page?.props?.setting?.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Register</title>
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
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
          <div class="card login-card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
              <!-- Logo / Heading -->
              <div class="text-center mb-3">
                <img
                  v-if="$page?.props?.setting?.logo"
                  :src="'/storage/upload_files/settings/' + $page.props.setting.logo"
                  alt="Logo"
                  style="max-width:200px; height:56px; object-fit:contain;"
                />
                <!--<h3 class="fw-semibold" style="margin:.75rem 0 .25rem;">Registrasi</h3>-->
                <br><br>
               <p
                  class="text-muted small"
                  style="
                    margin-bottom:.7rem;
                    text-align:center;
                    font-family:'Inter',sans-serif;
                    font-size:.9rem;
                    color:#6b7280;
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    gap:.45rem;
                    flex-wrap:wrap;
                  "
                >
                  <span>Punya akun?</span>
                  <Link
                    href='/login'
                    style="
                      display:inline-flex;
                      align-items:center;
                      justify-content:center;
                      gap:.4rem;
                      padding:.45rem 1rem;
                      border-radius:9999px;
                      background:linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);
                      color:#fff;
                      font-weight:600;
                      font-size:.9rem;
                      text-decoration:none;
                      box-shadow:0 3px 10px rgba(37,99,235,0.35);
                      cursor:pointer;
                      transition:all 0.3s ease;
                    "
                    onmouseover="this.style.transform='translateY(-2px) scale(1.03)'; this.style.boxShadow='0 8px 18px rgba(37,99,235,0.45)'; this.style.filter='brightness(1.05)';"
                    onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 3px 10px rgba(37,99,235,0.35)'; this.style.filter='none';"
                  >
                    <!-- Ikon login -->
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='white' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round' viewBox='0 0 24 24'>
                      <path d='M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4'/>
                      <polyline points='10 17 15 12 10 7'/>
                      <line x1='15' y1='12' x2='3' y2='12'/>
                    </svg>
                    <span>Login di sini</span>
                  </Link>
                </p>

              </div>

              <!-- Alerts -->
              <div v-if="Object.keys(errors || {}).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                <ul class="mt-2 mb-0">
                  <li v-for="(msg, key) in errors" :key="key">{{ msg }}</li>
                </ul>
              </div>
              <div v-if="$page?.props?.session?.error" class="alert alert-danger border-0 small" role="alert">
                <div v-html="$page.props.session.error"></div>
              </div>

              <form @submit.prevent="submit" novalidate>
                <!-- KODE -->
                <div class="mb-2" v-if="isActive('code')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('code') }}
                  </label>
                  <input
                    type="text"
                    v-model="form.code"
                    :class="['form-control', { 'is-invalid': !!errors?.code }]"
                    :placeholder="labelOf('code')"
                    :style="inputStyle"
                    :onfocus="onFocus"
                    :onblur="onBlur"
                  />
                  <div v-if="errors?.code" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.code }}
                  </div>
                </div>

                <!-- NAMA -->
                <div class="mb-2" v-if="isActive('name')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('name') }}
                  </label>
                  <input
                    type="text"
                    v-model="form.name"
                    :class="['form-control', { 'is-invalid': !!errors?.name }]"
                    :placeholder="labelOf('name')"
                    :style="inputStyle"
                    :onfocus="onFocus"
                    :onblur="onBlur"
                  />
                  <div v-if="errors?.name" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.name }}
                  </div>
                </div>

                <!-- EMAIL -->
                <div class="mb-2" v-if="isActive('email')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('email') }}
                  </label>
                  <input
                    type="email"
                    v-model="form.email"
                    :class="['form-control', { 'is-invalid': !!errors?.email }]"
                    :placeholder="labelOf('email')"
                    autocomplete="email"
                    :style="inputStyle"
                    :onfocus="onFocus"
                    :onblur="onBlur"
                  />
                  <div v-if="errors?.email" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.email }}
                  </div>
                  <small v-if="$page?.props?.setting?.notification_type == 2" class="text-muted">
                    <i>Pastikan {{ labelOf('email') }} yang didaftarkan aktif untuk menerima link aktivasi.</i>
                  </small>
                </div>

                <!-- USERNAME -->
                <div class="mb-2" v-if="isActive('username')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('username') }}
                  </label>
                  <input
                    type="text"
                    v-model="form.username"
                    :class="['form-control', { 'is-invalid': !!errors?.username }]"
                    :placeholder="labelOf('username')"
                    :style="inputStyle"
                    :onfocus="onFocus"
                    :onblur="onBlur"
                  />
                  <div v-if="errors?.username" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.username }}
                  </div>
                </div>

                <!-- PHONE -->
                <div class="mb-2" v-if="isActive('phone_number')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('phone_number') }}
                  </label>
                  <input
                    type="text"
                    v-model="form.phone_number"
                    :class="['form-control', { 'is-invalid': !!errors?.phone_number }]"
                    :placeholder="labelOf('phone_number')"
                    :style="inputStyle"
                    :onfocus="onFocus"
                    :onblur="onBlur"
                  />
                  <div v-if="errors?.phone_number" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.phone_number }}
                  </div>
                  <small v-if="$page?.props?.setting?.notification_type == 1" class="text-muted">
                    <i>Pastikan {{ labelOf('phone_number') }} yang didaftarkan aktif untuk menerima link aktivasi.</i>
                  </small>
                </div>

                <!-- PASSWORD -->
                <div class="mb-2" v-if="isActive('password')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('password') }}
                  </label>
                  <div class="position-relative">
                    <input
                      :type="showPassword ? 'text' : 'password'"
                      v-model="form.password"
                      :class="['form-control', { 'is-invalid': !!errors?.password }]"
                      :placeholder="labelOf('password')"
                      autocomplete="new-password"
                      :style="inputPasswordStyle"
                      :onfocus="onFocus"
                      :onblur="onBlur"
                    />
                    <button
                      type="button"
                      @click="toggleShowPassword"
                      :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                      style="position:absolute; right:10px; top:50%; transform:translateY(-50%); border:none; background:transparent; color:#64748b; font-size:1.1rem; cursor:pointer; padding:2px;"
                      onmouseover="this.style.color='#2563eb'"
                      onmouseout="this.style.color='#64748b'"
                    >
                      <i :class="showPassword ? 'bx bx-show' : 'bx bx-hide'"></i>
                    </button>
                    <div v-if="errors?.password" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                      {{ errors.password }}
                    </div>
                  </div>
                </div>

                <!-- PASSWORD CONFIRM -->
                <div class="mb-2" v-if="isActive('password')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ 'Konfirmasi ' + labelOf('password') }}
                  </label>
                  <div class="position-relative">
                    <input
                      :type="showPasswordConfirmation ? 'text' : 'password'"
                      v-model="form.password_confirmation"
                      :class="['form-control', { 'is-invalid': !!errors?.password_confirmation }]"
                      :placeholder="'Konfirmasi ' + labelOf('password')"
                      autocomplete="new-password"
                      :style="inputPasswordStyle"
                      :onfocus="onFocus"
                      :onblur="onBlur"
                    />
                    <button
                      type="button"
                      @click="toggleShowPasswordConfirmation"
                      :aria-label="showPasswordConfirmation ? 'Sembunyikan password' : 'Tampilkan password'"
                      style="position:absolute; right:10px; top:50%; transform:translateY(-50%); border:none; background:transparent; color:#64748b; font-size:1.1rem; cursor:pointer; padding:2px;"
                      onmouseover="this.style.color='#2563eb'"
                      onmouseout="this.style.color='#64748b'"
                    >
                      <i :class="showPasswordConfirmation ? 'bx bx-show' : 'bx bx-hide'"></i>
                    </button>
                    <div v-if="errors?.password_confirmation" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                      {{ errors.password_confirmation }}
                    </div>
                  </div>
                </div>

                <!-- GENDER -->
                <div class="mb-2" v-if="isActive('gender')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('gender') }}
                  </label>
                  <select
                    v-model="form.gender"
                    :class="['form-select', { 'is-invalid': !!errors?.gender }]"
                    style="border-radius:10px; height:38px; font-size:.9rem; padding:8px 12px; border:1px solid #d1d5db; transition:all .2s ease;"
                    onfocus="this.style.border='1px solid #2563eb'; this.style.boxShadow='0 0 0 2px rgba(37,99,235,0.15)'"
                    onblur="this.style.border='1px solid #d1d5db'; this.style.boxShadow='none'"
                  >
                    <option value="">[ Pilih ]</option>
                    <option value="M">Laki-laki</option>
                    <option value="F">Perempuan</option>
                  </select>
                  <div v-if="errors?.gender" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.gender }}
                  </div>
                </div>

                <!-- PROVINCE -->
                <div class="mb-2" v-if="isActive('province_id')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('province_id') }}
                  </label>
                  <select
                    @change="cityData"
                    v-model="form.province_id"
                    :class="['form-select', { 'is-invalid': !!errors?.province_id }]"
                    style="border-radius:10px; height:38px; font-size:.9rem; padding:8px 12px; border:1px solid #d1d5db; transition:all .2s ease;"
                    onfocus="this.style.border='1px solid #2563eb'; this.style.boxShadow='0 0 0 2px rgba(37,99,235,0.15)'"
                    onblur="this.style.border='1px solid #d1d5db'; this.style.boxShadow='none'"
                  >
                    <option value="">[ Pilih ]</option>
                    <option v-for="province in provinces" :key="province.id" :value="province.id">{{ province.name }}</option>
                  </select>
                  <div v-if="errors?.province_id" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.province_id }}
                  </div>
                </div>

                <!-- CITY -->
                <div class="mb-2" v-if="isActive('city_id')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('city_id') }}
                  </label>
                  <select
                    @change="districtData"
                    v-model="form.city_id"
                    :class="['form-select', { 'is-invalid': !!errors?.city_id }]"
                    style="border-radius:10px; height:38px; font-size:.9rem; padding:8px 12px; border:1px solid #d1d5db; transition:all .2s ease;"
                    onfocus="this.style.border='1px solid #2563eb'; this.style.boxShadow='0 0 0 2px rgba(37,99,235,0.15)'"
                    onblur="this.style.border='1px solid #d1d5db'; this.style.boxShadow='none'"
                  >
                    <option value="">[ Pilih ]</option>
                    <option v-for="city in form.cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                  </select>
                  <div v-if="errors?.city_id" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.city_id }}
                  </div>
                </div>

                <!-- DISTRICT -->
                <div class="mb-2" v-if="isActive('district_id')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('district_id') }}
                  </label>
                  <select
                    @change="villageData"
                    v-model="form.district_id"
                    :class="['form-select', { 'is-invalid': !!errors?.district_id }]"
                    style="border-radius:10px; height:38px; font-size:.9rem; padding:8px 12px; border:1px solid #d1d5db; transition:all .2s ease;"
                    onfocus="this.style.border='1px solid #2563eb'; this.style.boxShadow='0 0 0 2px rgba(37,99,235,0.15)'"
                    onblur="this.style.border='1px solid #d1d5db'; this.style.boxShadow='none'"
                  >
                    <option value="">[ Pilih ]</option>
                    <option v-for="district in form.districts" :key="district.id" :value="district.id">{{ district.name }}</option>
                  </select>
                  <div v-if="errors?.district_id" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.district_id }}
                  </div>
                </div>

                <!-- VILLAGE -->
                <div class="mb-2" v-if="isActive('village_id')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('village_id') }}
                  </label>
                  <select
                    v-model="form.village_id"
                    :class="['form-select', { 'is-invalid': !!errors?.village_id }]"
                    style="border-radius:10px; height:38px; font-size:.9rem; padding:8px 12px; border:1px solid #d1d5db; transition:all .2s ease;"
                    onfocus="this.style.border='1px solid #2563eb'; this.style.boxShadow='0 0 0 2px rgba(37,99,235,0.15)'"
                    onblur="this.style.border='1px solid #d1d5db'; this.style.boxShadow='none'"
                  >
                    <option value="">[ Pilih ]</option>
                    <option v-for="village in form.villages" :key="village.id" :value="village.id">{{ village.name }}</option>
                  </select>
                  <div v-if="errors?.village_id" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.village_id }}
                  </div>
                </div>

                <!-- ADDRESS -->
                <div class="mb-2" v-if="isActive('address')">
                  <label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">
                    {{ labelOf('address') }}
                  </label>
                  <textarea
                    v-model="form.address"
                    :class="['form-control', { 'is-invalid': !!errors?.address }]"
                    :placeholder="labelOf('address')"
                    style="border-radius:10px; font-size:.9rem; padding:10px 12px; border:1px solid #d1d5db; min-height:88px; transition:all .2s ease;"
                    onfocus="this.style.border='1px solid #2563eb'; this.style.boxShadow='0 0 0 2px rgba(37,99,235,0.15)'"
                    onblur="this.style.border='1px solid #d1d5db'; this.style.boxShadow='none'"
                  ></textarea>
                  <div v-if="errors?.address" class="invalid-feedback" style="font-size:.8rem; margin-top:2px;">
                    {{ errors.address }}
                  </div>
                </div>

                <!-- Referral (read-only) -->
                <div class="mb-2" v-if="$page?.props?.setting?.enable_affiliate_feature == 1 && referrer && referrer.referral_link">
                  <!--<label class="form-label" style="font-size:.9rem; margin-bottom:.25rem;">Kode Referral</label>-->
                  <input type="hidden" class="form-control" :value="referrer.referral_link.referral_code" readonly :style="inputStyle">
                </div>
                <br>
                <!-- Submit -->
                <button
                  type="submit"
                  class="w-100"
                  style="
                    background: linear-gradient(90deg, #2563eb, #1d4ed8);
                    color: #ffffff;
                    font-weight: 600;
                    font-size: 1.05rem;
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
                  <i class="bx bx-user" style="margin-right:6px;"></i> Registrasi
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dim layer ketika pakai background image -->
    <div v-if="$page?.props?.setting?.authentication_background" class="dim" aria-hidden="true"></div>
  </div>
</template>

<script>
import LayoutAuth from '../../Layouts/Auth.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { reactive, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import axios from 'axios'

export default {
  layout: LayoutAuth,
  components: { Head, Link },
  props: {
    errors: { type: Object, default: () => ({}) },
    session: { type: Object, default: () => ({}) },
    referrer: { type: Object, default: () => null }
  },
  data () {
    return {
      provinces: []
    }
  },
  mounted () {
    axios.get('region/province')
      .then(res => { this.provinces = res.data })
      .catch(console.error)
  },
  setup () {
    const showPassword = ref(false)
    const showPasswordConfirmation = ref(false)
    const form = reactive({
      code: '',
      name: '',
      email: '',
      username: '',
      password: '',
      password_confirmation: '',
      province_id: '',
      city_id: '',
      district_id: '',
      village_id: '',
      address: '',
      phone_number: '',
      gender: '',
      // data region cascade
      cities: [],
      districts: [],
      villages: []
    })

    const toggleShowPassword = () => { showPassword.value = !showPassword.value }
    const toggleShowPasswordConfirmation = () => { showPasswordConfirmation.value = !showPasswordConfirmation.value }

    const cityData = () => {
      form.city_id = ''; form.district_id = ''; form.village_id = ''
      axios.get(`/region/city/${form.province_id}`).then(r => { form.cities = r.data }).catch(console.error)
    }
    const districtData = () => {
      form.district_id = ''; form.village_id = ''
      axios.get(`/region/district/${form.city_id}`).then(r => { form.districts = r.data }).catch(console.error)
    }
    const villageData = () => {
      form.village_id = ''
      axios.get(`/region/village/${form.district_id}`).then(r => { form.villages = r.data }).catch(console.error)
    }

    const submit = () => {
      Inertia.post('/register', {
        code: form.code,
        name: form.name,
        email: form.email,
        username: form.username,
        password: form.password,
        password_confirmation: form.password_confirmation,
        province_id: form.province_id,
        city_id: form.city_id,
        district_id: form.district_id,
        village_id: form.village_id,
        address: form.address,
        phone_number: form.phone_number,
        gender: form.gender
      })
    }

    // inline style & focus handlers reused
    const inputStyle =
      "border-radius:10px; padding:8px 12px; font-size:0.9rem; height:38px; line-height:1.3; border:1px solid #d1d5db; transition:all .2s ease;"
    const inputPasswordStyle =
      "border-radius:10px; padding:8px 38px 8px 12px; font-size:0.9rem; height:38px; line-height:1.3; border:1px solid #d1d5db; transition:all .2s ease;"
    const onFocus = "this.style.border='1px solid #2563eb'; this.style.boxShadow='0 0 0 2px rgba(37,99,235,0.15)'"
    const onBlur  = "this.style.border='1px solid #d1d5db'; this.style.boxShadow='none'"

    return {
      form,
      submit,
      cityData,
      districtData,
      villageData,
      showPassword,
      toggleShowPassword,
      showPasswordConfirmation,
      toggleShowPasswordConfirmation,
      inputStyle,
      inputPasswordStyle,
      onFocus,
      onBlur
    }
  },
  computed: {
    safeSetting () {
      const s = this.$page?.props?.setting || {}
      return {
        fields: Array.isArray(s.authentication_field) ? s.authentication_field : []
      }
    },
    isActive () {
      return (name) => {
        return this.safeSetting.fields.some(f => f.name === name && String(f.is_active) === '1')
      }
    },
    labelOf () {
      return (name) => {
        return this.safeSetting.fields.find(f => f.name === name)?.translate || name
      }
    }
  }
}
</script>

<style scoped>
/* Wrapper & dim layer — sama dengan halaman login modern */
:root { --ink: #0f172a; --paper: #ffffff; --bg: #f5f7fb; }
.wrap { position: relative; min-height: 100vh; background: var(--bg); background-size: cover; background-position: center; }
.wrap.has-bg { background-color: #0b1220; }
.dim { position: fixed; inset: 0; background: rgba(0,0,0,.45); pointer-events: none; z-index: 1; }
.content-layer { position: relative; z-index: 2; }

/* Card solid agar kontras aman pada foto */
.login-card { border-radius: 16px; background: var(--paper); }
.card-body { color: var(--ink); }
</style>
