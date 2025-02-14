<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Register</title>
    </Head>

    <div class="authentication-reset-password pt-3 align-items-center justify-content-center"
        :class="{ 'has-background': $page.props.setting.authentication_background }"
        :style="{ backgroundImage: $page.props.setting.authentication_background ? `url('/storage/upload_files/settings/${$page.props.setting.authentication_background}')` : '' }">
        
        <div class="container">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 mb-5">
                <div class="col mx-auto">
                    <div class="my-4 text-center">
                        <img src="/assets/images/logo-img.png" width="180" alt="" />
                    </div>
                    <div class="card">
                        <div class="card-body" :style="{ background: $page.props.setting.register_page_background ?? '#fff' }">
                            <div class="border p-4 rounded">

                                <div class="text-center">
                                    <img v-if="$page.props.setting && $page.props.setting.logo" v-bind:src="'/storage/upload_files/settings/' + $page.props.setting.logo" style="max-width: 80%; height: 80px;"/>
                                </div>

                                <br>

                                <div class="text-center">
                                    <h3 class="">Registrasi</h3>
                                    <p>Sudah memiliki akun ? <Link href="/login">Login disini</Link>
                                    </p>
                                </div>
                                <div class="form-body">
                                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                                        <ul class="mt-3">
                                            <li v-for="error in errors">{{ error }}</li>
                                        </ul>
                                    </div>
                                    <div v-if="$page.props.session.error" class="alert alert-danger border-0">
                                        <i class="fa fa-exclamation-triangle"></i>  <div v-html="$page.props.session.error"></div>
                                    </div>
                                    <form @submit.prevent="submit" class="row g-3">
                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'code' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'code')?.translate }}</label>
                                            <input type="text" v-model="form.code" :class="{ 'is-invalid': errors.code }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'code')?.translate">
                                            <div v-if="errors.code" class="invalid-feedback">
                                                {{ errors.code }}
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'name' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'name')?.translate }}</label>
                                            <input type="text" v-model="form.name" :class="{ 'is-invalid': errors.name }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'name')?.translate">
                                            <div v-if="errors.name" class="invalid-feedback">
                                                {{ errors.name }}
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'email' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }}</label>
                                            <input type="email" v-model="form.email" :class="{ 'is-invalid': errors.email }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'email')?.translate">
                                            <div v-if="errors.email" class="invalid-feedback">
                                                {{ errors.email }}
                                            </div>
                                            <small v-if="$page.props.setting.notification_type == 2"><i>Pastikan {{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }} yang didaftarkan aktif untuk menerima link aktivasi.</i></small>
                                        </div>
                                        
                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'username' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'username')?.translate }}</label>
                                            <input type="text" v-model="form.username" :class="{ 'is-invalid': errors.username }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'username')?.translate">
                                            <div v-if="errors.username" class="invalid-feedback">
                                                {{ errors.username }}
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'phone_number' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'phone_number')?.translate }}</label>
                                            <input type="text" v-model="form.phone_number" :class="{ 'is-invalid': errors.phone_number }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'phone_number')?.translate">
                                            <div v-if="errors.phone_number" class="invalid-feedback">
                                                {{ errors.phone_number }}
                                            </div>
                                            <small v-if="$page.props.setting.notification_type == 1"><i>Pastikan {{ $page.props.setting.authentication_field.find(field => field.name == 'email')?.translate }} yang di daftarkan aktif untuk menerima link aktivasi.</i></small>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'password' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</label>
                                            <div class="input-group">
                                                <input v-if="showPassword" type="text" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <input v-else type="password" v-model="form.password" :class="{ 'is-invalid': errors.password }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <span class="input-group-text" @click="toggleShowPassword"><i :class="{ 'bx bx-show': showPassword, 'bx bx-hide': !showPassword }"></i></span>     
                                                <div v-if="errors.password" class="invalid-feedback">
                                                    {{ errors.password }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'password' && field.is_active == '1')">
                                            <label class="form-label">Konfirmasi {{ $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate }}</label>
                                            <div class="input-group">
                                                <input v-if="showPasswordConfirmation" type="text" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" class="form-control" :placeholder="'Konfirmasi ' + $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <input v-else type="password" v-model="form.password_confirmation" :class="{ 'is-invalid': errors.password_confirmation }" class="form-control" :placeholder="'Konfirmasi ' + $page.props.setting.authentication_field.find(field => field.name == 'password')?.translate">
                                                <span class="input-group-text" @click="toggleShowPasswordConfirmation"><i :class="{ 'bx bx-show': showPasswordConfirmation, 'bx bx-hide': !showPasswordConfirmation }"></i></span>     
                                                <div v-if="errors.password_confirmation" class="invalid-feedback">
                                                    {{ errors.password_confirmation }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'gender' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'gender')?.translate }}</label>
                                            <select v-model="form.gender" :class="{ 'is-invalid': errors.gender }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option value="M">Laki-laki</option>
                                                <option value="F">Perempuan</option>
                                            </select>
                                            <div v-if="errors.gender" class="invalid-feedback">
                                                {{ errors.gender }}
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'province_id' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'province_id')?.translate }}</label>
                                            <select  @change="cityData" v-model="form.province_id" :class="{ 'is-invalid': errors.province_id }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option v-for="province in provinces" :value="province.id">
                                                    {{ province.name }}
                                                </option>
                                            </select>
                                            <div v-if="errors.province_id" class="invalid-feedback">
                                                {{ errors.province_id }}
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'city_id' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'city_id')?.translate }}</label>
                                            <select @change="districtData" v-model="form.city_id" :class="{ 'is-invalid': errors.city_id }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option v-for="city in form.cities" :value="city.id">
                                                {{ city.name }}
                                                </option>
                                            </select>

                                            <div v-if="errors.city_id" class="invalid-feedback">
                                                {{ errors.city_id }}
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'district_id' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'district_id')?.translate }}</label>
                                            <select @change="villageData" v-model="form.district_id" :class="{ 'is-invalid': errors.district_id }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option v-for="district in form.districts" :value="district.id">
                                                {{ district.name }}
                                                </option>
                                            </select>
                                            <div v-if="errors.district_id" class="invalid-feedback">
                                                {{ errors.district_id }}
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'village_id' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'village_id')?.translate }}</label>
                                            <select v-model="form.village_id" :class="{ 'is-invalid': errors.village_id }" class="form-select">
                                                <option value="">[ Pilih ]</option>
                                                <option v-for="village in form.villages" :value="village.id">
                                                {{ village.name }}
                                                </option>
                                            </select>
                                            <div v-if="errors.village_id" class="invalid-feedback">
                                                {{ errors.village_id }}
                                            </div>
                                        </div>

                                        <div class="col-12" v-if="$page.props.setting.authentication_field.some(field => field.name == 'address' && field.is_active == '1')">
                                            <label class="form-label">{{ $page.props.setting.authentication_field.find(field => field.name == 'address')?.translate }}</label>
                                            <textarea cols="30" rows="10" v-model="form.address" :class="{ 'is-invalid': errors.address }" class="form-control" :placeholder="$page.props.setting.authentication_field.find(field => field.name == 'address')?.translate"></textarea>
                                            <div v-if="errors.address" class="invalid-feedback">
                                                {{ errors.address }}
                                            </div>
                                        </div>  

                                        <div class="col-12" v-if="$page.props.setting.enable_affiliate_feature == 1 && referrer && referrer.referral_link">
                                            <label class="form-label">Kode Referral</label>
                                            <input type="text" class="form-control" :value="referrer.referral_link.referral_code" readonly>
                                        </div>

                                        <!-- <div class="col-lg-6 col-md-12" v-if="$page.props.setting.enable_affiliate_feature == 1 && referrer">
                                            <label class="form-label">Nama Afiliasi</label>
                                            <input type="text" class="form-control" :value="referrer.name" readonly>
                                        </div> -->
                                        
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary"><i class='bx bx-user'></i>Registrasi</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
</template>

<style>
    .authentication-reset-password {
        justify-content: center;
        align-items: center;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        height: auto;
    }

    .authentication-reset-password.has-background::before {
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

    //import axios
    import axios from 'axios';

    export default {
        // layout
        layout: LayoutAuth,

        components: {
            Head,
            Link
        },
        data() {
            return {
                cities: [],
                provinces: [],
            }
        },

        //props
        props: {
            errors: Object,
            session: Object,
            cities: Object,
            referrer: Object,
        },
        mounted() {
            // get all provinces data
            axios.get('region/province').then(response => {
                this.provinces = response.data;
            }).catch(error => console.error(error));
        },

        setup(props) {

            const showPassword = ref(false);
            const showPasswordConfirmation = ref(false);

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
                // get from api
                cities: [],
                districts: [],
                villages: [],
            });

            const toggleShowPassword = () => {
                showPassword.value = !showPassword.value; // Update the value using .value with ref
            };

            const toggleShowPasswordConfirmation = () => {
                showPasswordConfirmation.value = !showPasswordConfirmation.value; 
            };

            const cityData = () => {
                form.city_id = '';
                form.district_id = '';
                form.village_id = '';
                axios.get(`/region/city/${form.province_id}`).then(response => {
                form.cities = response.data;
                }).catch(error => console.error(error));
            }

            const districtData = () => {
                form.district_id = '';
                form.village_id = '';
                axios.get(`/region/district/${form.city_id}`).then(response => {
                form.districts = response.data;
                }).catch(error => console.error(error));
            }

            const villageData = () => {
                form.village_id = '';
                axios.get(`/region/village/${form.district_id}`).then(response => {
                form.villages = response.data;
                }).catch(error => console.error(error));
            }

            // submit method
            const submit = () => {
                // send data to server
                Inertia.post('/register', {
                    // data
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
                    gender: form.gender,
                });
            }

            // return form state and submit method
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
            }
        }
    }
</script>
