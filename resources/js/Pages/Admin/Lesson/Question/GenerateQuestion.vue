<template>
    <Head>
        <title>{{ $page.props.setting.app_name ?? 'Atur Setting Terlebih Dahulu' }} - Tambah Generate Soal Kecermatan</title>
    </Head>
    <div class="page-wrapper">
        <div class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Generate Soal Kecermatan</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Soal Kecermatan</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="card border-top border-0 border-3 border-primary">
                <div class="card-body">
                    <div v-if="$page.props.session.error" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Gagal</strong>
                        <ul class="mt-3">
                            <li>{{ $page.props.session.error }}</li>
                        </ul>
                    </div>

                    <div v-if="Object.keys(errors).length > 0" class="alert alert-danger border-0 alert-dismissible fade show mb-3 p-0 px-3 py-2">
                        <strong>Perhatian, pastikan inputan diisi dengan benar.</strong>
                        <ul class="mt-3">
                            <li v-for="error in Object.values(errors).flat()" :key="error">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="d-lg-flex align-items-center">
                        <div class="ms-auto">
                            <a href="/assets/import_format/format-import-kecermatan.xlsx" class="btn btn-success mx-1 btn-sm mt-lg-0" download><i class="bx bxs-file"></i>Download Format Import Kecermatan</a>
                            <Link :href="`/admin/question-titles/${questionTitle.id}/questions`" class="btn btn-primary btn-sm mt-2 mt-lg-0">Kembali</Link>
                        </div>
                    </div>
                    <form @submit.prevent="submit" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Sumber Data Isian</label>
                            <select v-model="form.source_data" :class="{ 'is-invalid': errors.source_data }" class="form-select">
                                <option value="">[ Pilih ]</option>
                                <option value="1">Disi Manual</option>
                                <option value="2">Diisi Menggunakan File Template Excel</option>
                            </select>
                            <div v-if="errors.source_data" class="invalid-feedback">
                                {{ errors.source_data }}
                            </div>
                        </div>

                        <div class="col-12" v-if="form.source_data == 2">
                            <label class="form-label">Template Excel</label>
                            <input type="file" class="form-control" @change="handleFileChange" :class="{ 'is-invalid': errors.template }" placeholder="template" id="fileInput">
                            <div v-if="errors.template" class="invalid-feedback">
                                {{ errors.template }}
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Template Soal</label>
                            <div class="input-group">
                                <select v-model="form.question_template_id" :class="{ 'is-invalid': errors.question_template_id }" class="form-select">
                                    <option value="">[ Pilih ]</option>
                                    <option v-for="(questionTemplate, index) in questionTemplates" :key="index" :value="questionTemplate.id">
                                        {{ questionTemplate.name }}
                                    </option>
                                </select>
                                <Link :href="`/admin/question-titles/${questionTitle.id}/question-templates`" class="btn btn-primary" id="button-addon2">Tambah Template</Link>
                                <div v-if="errors.question_template_id" class="invalid-feedback">
                                    {{ errors.question_template_id }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12" v-if="form.source_data == 1">
                            <label class="form-label">Total Soal Per Kolom (Minimal 50 Per Kolom)</label>
                            <input type="number" class="form-control"  v-model="form.total_question_per_section" :class="{ 'is-invalid': errors.total_question_per_section }" placeholder="Total Soal Per Kolom">
                            <div v-if="errors.total_question_per_section" class="invalid-feedback">
                                {{ errors.total_question_per_section }}
                            </div>
                        </div>
                        <div class="col-6" v-for="index in 10" :key="index" v-if="form.source_data == 1">
                            <label class="form-label">Kolom {{ index }}</label>
                            <input type="text" class="form-control" v-model="form[`column_${index}`]" @input="validateColumn(`column_${index}`)" :class="{ 'is-invalid': errors[`column_${index}`] }" placeholder="Ketik Maksimal 5 Angka, Huruf atau Simbol. Nilai Bisa Dicampur.">
                            <div v-if="errors[`column_${index}`]" class="invalid-feedback">
                                {{ errors[`column_${index}`] }}
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            <button type="button" class="btn btn-sm btn-danger mx-2" @click="resetForm">Reset</button>
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#generateAutomaticQuestion" v-if="form.source_data == 1">Soal Otomatis</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="generateAutomaticQuestion" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Soal Otomatis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                Kolom soal akan terisi otomatis berdasarkan huruf, angka dan simbol yang dipilih.
                            </p>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td>Angka</td>
                                    <td><input type="checkbox" value="number" v-model="selection"></td>
                                </tr>
                                <tr>
                                    <td>Huruf</td>
                                    <td><input type="checkbox" value="letter" v-model="selection"></td>
                                </tr>
                                <tr>
                                    <td>Simbol</td>
                                    <td><input type="checkbox" value="symbol" v-model="selection"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="confirmSelection">Lanjutkan</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LayoutAdmin from '../../../../Layouts/Layout.vue';
    import { Link } from '@inertiajs/inertia-vue3';
    import { reactive, ref, watch } from 'vue';
    import Swal from 'sweetalert2';
    import { Head } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia';

    export default {
        layout: LayoutAdmin,
        components: {
            Link,
            Head,
        },
        props: {
            errors: Object,
            lessons: Object,
            questionTemplates: Object,
            questionTitle: Object,
        },
        setup(props) {
            const form = reactive({
                source_data: '',
                question_template_id: '',
                total_question_per_section: '50',
                template: null,
                column_1: '',
                column_2: '',
                column_3: '',
                column_4: '',
                column_5: '',
                column_6: '',
                column_7: '',
                column_8: '',
                column_9: '',
                column_10: '',
            });

            const errors = ref({ ...props.errors });
            const selection = ref([]);

            watch(() => props.errors, (newErrors) => {
                errors.value = { ...newErrors };
            });

            const handleFileChange = (event) => {
                form.template = event.target.files[0];
            };

            const validateColumn = (column) => {
                const value = form[column];
                if (value.length !== 5) {
                    errors.value[column] = `Kolom ${column.replace("column_", "")} harus memiliki 5 karakter.`;
                } else if (new Set(value).size !== value.length) {
                    errors.value[column] = `Kolom ${column.replace("column_", "")} tidak boleh ada karakter yang sama.`;
                } else {
                    delete errors.value[column];
                }
            };

            const validate = () => {
                errors.value = {};
                if (!form.source_data) {
                    errors.value.source_data = 'Sumber Data Isian tidak boleh kosong';
                }
                if (!form.question_template_id) {
                    errors.value.question_template_id = 'Template Soal tidak boleh kosong';
                }
                if (form.source_data == 1 && !form.total_question_per_section) {
                    errors.value.total_question_per_section = 'Total Soal tidak boleh kosong';
                }
                if (form.source_data == 1 && parseInt(form.total_question_per_section) < 50) {
                    errors.value.total_question_per_section = 'Total Soal tidak boleh kurang dari 50 soal per kolom';
                }
                if (form.source_data == 2 && !form.template) {
                    errors.value.template = 'Template Excel harus diunggah';
                }
                if (form.source_data == 1) {
                    for (let i = 1; i <= 10; i++) {
                        validateColumn(`column_${i}`);
                    }
                }
                return Object.keys(errors.value).length === 0;
            };

            const generateQuestion = () => {
                const numbers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
                const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
                const symbols = ['α','β','γ','δ','ε','ζ','η','θ','ι','κ','λ','μ','ν','ξ','ο','π','ρ','ς','σ','τ','υ','φ','χ','ψ','ω','Γ','Δ','Θ','Λ','Ξ','Π','Σ','Φ','Ψ','Ω'];

                let pool = [];
                if (selection.value.includes('number')) pool = pool.concat(numbers);
                if (selection.value.includes('letter')) pool = pool.concat(letters);
                if (selection.value.includes('symbol')) pool = pool.concat(symbols);

                for (let i = 1; i <= 10; i++) {
                    form[`column_${i}`] = generateUniqueValue(pool, 5);
                }
            };

            const generateUniqueValue = (pool, length) => {
                let result = '';
                while (result.length < length) {
                    const char = pool[Math.floor(Math.random() * pool.length)];
                    if (!result.includes(char)) {
                        result += char;
                    }
                }
                return result;
            };

            const confirmSelection = () => {
                generateQuestion();
                const modal = bootstrap.Modal.getInstance(document.getElementById('generateAutomaticQuestion'));
                modal.hide();
            };

            const resetForm = () => {
                form.source_data = '';
                form.question_template_id = '';
                form.total_question_per_section = '';
                form.template = null;
                for (let i = 1; i <= 10; i++) {
                    form[`column_${i}`] = '';
                }
                errors.value = {};
            };

            const submit = () => {
                if (!validate()) return;

                const formData = new FormData();
                formData.append('source_data', form.source_data);
                formData.append('total_question_per_section', form.total_question_per_section);
                formData.append('question_template_id', form.question_template_id);
                formData.append('template', form.template);
                for (let i = 1; i <= 10; i++) {
                    formData.append(`column_${i}`, form[`column_${i}`]);
                }

                Inertia.post(`/admin/question-titles/${props.questionTitle.id}/questions/generate-questions`, formData, {
                    onSuccess: (page) => {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Generate Soal Kecermatan dijalankan!.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        // Reset errors
                        errors.value = {};
                    },
                    onFinish: visit => {
                        form.template = '';
                        document.querySelector('#fileInput').value = '';
                    },
                    onError: (serverErrors) => {
                        // Handle backend validation errors
                        errors.value = { ...serverErrors };
                    },
                });
            };

            return {
                form,
                errors,
                submit,
                validateColumn,
                selection,
                confirmSelection,
                handleFileChange,
                generateQuestion,
                resetForm,
            };
        },
    };
</script>
