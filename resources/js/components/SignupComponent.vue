<template>
    <section class="section">
        <div class="columns is-centered">
            <div class="column is-8">

                <div class="reg-form-container">
                    <div class="has-text-weight-bold is-size-4 mb-3">
                        REGISTER HERE
                    </div>

                    <form @submit.prevent="submit">

                        <div class="columns">
                            <div class="column">
                                <b-field label="Account Type" expanded
                                    :type="this.errors.role ? 'is-danger':''"
                                    :message="this.errors.role ? this.errors.role[0] : ''">
                                    <b-select placeholder="Account Type" v-model="fields.role" icon="account" expanded>
                                        <option value="STUDENT">STUDENT</option>
                                        <option value="FACULTY">FACULTY</option>
                                        <option value="VENDOR">VENDOR</option>
                                    </b-select>
                                </b-field>
                            </div>
                        </div>

                        <!-- <div class="columns">
                            <div class="column">
                                <b-upload v-model="file" class="file-label">
                                    <span class="file-cta">
                                        <b-icon class="file-icon" icon="upload"></b-icon>
                                        <span class="file-label">Click to upload</span>
                                    </span>
                                    <span class="file-name" v-if="file">
                                        {{ file.name }}
                                    </span>
                                </b-upload>
                            </div>
                        </div> -->
                        <hr>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Username"
                                         :type="this.errors.username ? 'is-danger':''"
                                         :message="this.errors.username ? this.errors.username[0] : ''">
                                    <b-input type="text" v-model="fields.username" placeholder="Username" icon="account"></b-input>
                                </b-field>
                            </div>

                            <div class="column" v-if="fields.role === 'STUDENT'">
                                <b-field label="Student ID"
                                         :type="this.errors.student_id ? 'is-danger':''"
                                         :message="this.errors.student_id ? this.errors.student_id[0] : ''">
                                    <b-input type="text" v-model="fields.student_id" placeholder="Student ID" icon="account"></b-input>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Password"
                                         :type="this.errors.password ? 'is-danger':''"
                                         :message="this.errors.password ? this.errors.password[0] : ''">
                                    <b-input type="password"  v-model="fields.password" icon="lock" placeholder="Password" password-reveal></b-input>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="Re-type Password">
                                    <b-input type="password" icon="lock" v-model="fields.password_confirmation" placeholder="Password" password-reveal></b-input>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Email"
                                         :type="this.errors.email ? 'is-danger':''"
                                         :message="this.errors.email ? this.errors.email[0] : ''">
                                    <b-input type="email" v-model="fields.email" placeholder="Email" icon="email"></b-input>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="Contact No."
                                         :type="this.errors.contact_no ? 'is-danger':''"
                                         :message="this.errors.contact_no ? this.errors.contact_no[0] : ''">
                                    <b-input type="text" v-model="fields.contact_no" placeholder="Contact No." icon=""></b-input>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Last Name"
                                         :type="this.errors.lname ? 'is-danger':''"
                                         :message="this.errors.lname ? this.errors.lname[0] : ''" >
                                    <b-input icon="account" placeholder="First Lastname" v-model="fields.lname" type="text"></b-input>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="First Name"
                                         :type="this.errors.fname ? 'is-danger':''"
                                         :message="this.errors.fname ? this.errors.fname[0] : ''">
                                    <b-input icon="account" v-model="fields.fname" placeholder="First Name" type="text"></b-input>
                                </b-field>
                            </div>
                        </div>


                        <div class="columns">
                            <div class="column">
                                <b-field label="Middle Name">
                                    <b-input v-model="fields.mname" type="text" placeholder="Middle Name"></b-input>
                                </b-field>
                            </div>

                            <div class="column">
                                <b-field label="Suffix">
                                    <b-input type="text" v-model="fields.suffix" placeholder="Suffix"></b-input>
                                </b-field>
                            </div>

                            <div class="column">
                                <b-field label="Sex" expanded
                                         :type="this.errors.sex ? 'is-danger':''"
                                         :message="this.errors.sex ? this.errors.sex[0] : ''">
                                    <b-select placeholder="Sex" v-model="fields.sex" icon="account" expanded>
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                    </b-select>
                                </b-field>
                            </div>
                        </div>



                        <div class="columns">
                            <div class="column">
                                <b-field label="Province" expanded
                                         :type="this.errors.province ? 'is-danger':''"
                                         :message="this.errors.province ? this.errors.province[0] : ''">
                                    <b-select v-model="fields.province" expanded placeholder="Province" @input="loadCity">
                                        <option v-for="(item, index) in provinces" :key="index" :value="item.provCode">{{ item.provDesc }}</option>
                                    </b-select>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="City/Municipality" expanded
                                         :type="this.errors.city ? 'is-danger':''"
                                         :message="this.errors.city ? this.errors.city[0] : ''">
                                    <b-select expanded v-model="fields.city" placeholder="City" @input="loadBarangay">
                                        <option v-for="(item, index) in cities" :key="index" :value="item.citymunCode">{{ item.citymunDesc }}</option>
                                    </b-select>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Barangay" expanded
                                         :type="this.errors.barangay ? 'is-danger':''"
                                         :message="this.errors.barangay ? this.errors.barangay[0] : ''">
                                    <b-select v-model="fields.barangay" expanded placeholder="Barangay">
                                        <option v-for="(item, index) in barangays" :key="index" :value="item.brgyCode">{{ item.brgyDesc }}</option>
                                    </b-select>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="House #. Street">
                                    <b-input type="text" v-model="fields.street"></b-input>
                                </b-field>
                            </div>
                        </div>

                        <div class="buttons is-right">
                            <button class="button is-primary">Register</button>
                        </div>
                    </form>
                </div><!--reg-form-container-->
            </div><!--column-->
        </div><!--cols-->
    </section>
</template>

<script>
export default {

    data(){
        return{

            fields: {
                username: '',
                password: '',
                student_id: '',
                lname: '',
                fname: '',
                mname: '',
                suffix: '',
                sex: '',
                contact_no: '',
                email: '',

                province: '',
                city: '',
                barangay: '',
                street: '',
                role: 'STUDENT'
            },



            errors: {},

            provinces: [],
            cities: [],
            barangays: [],
        }
    },
    methods: {

        clearFields(){
            this.field = {
                username: '',
                password: '',
                student_id: '',
                lname: '',
                fname: '',
                mname: '',
                suffix: '',
                sex: '',
                contact_no: '',
                email: '',

                province: '',
                city: '',
                barangay: '',
                street: '',
                role: 'STUDENT'
            };
        },


        //ADDRESS
        loadProvince: function(){
            axios.get('/load-provinces').then(res=>{
                this.provinces = res.data;
            })
        },
        loadCity: function(){
            axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                this.cities = res.data;
            })
        },

        loadBarangay: function(){
            axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                this.barangays = res.data;
            })
        },

        submit(){
            this.fields.student_id = this.fields.role === 'FACULTY' ? '': this.fields.student_id;

            axios.post('/sign-up', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: "SAVED!",
                        message: 'Register successfully',
                        type: 'is-success',
                        onConfirm: ()=>  window.location = '/'
                    });
                }

            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }else{
                    alert('An error occured.');
                }
            });
        },

    },
    mounted() {
        this.clearFields();
        this.loadProvince();
    }
}
</script>

<style scoped>
    .reg-form-container{
        background-color: white;
    }

/*    dere lang kubia ang panel color*/
</style>
