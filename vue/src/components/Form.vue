<template>
  <div class="container">
    <div class="row">
      <div class="col"> 
        <div class="form">
          <div class="mb-3">
            <label class="form-label">Imię</label>
            <input @input="$v.name.$touch()" :class="{'is-invalid': $v.name.$dirty &&  $v.name.$invalid}" type="text" class="form-control" v-model="name">
            <span v-if="$v.name.$dirty && !$v.name.required">Pole jest wymagane</span>
            <span v-if="$v.name.$dirty && !$v.name.minLength">Pole musi zawierac min 2 znaki</span>
          </div>
          <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" v-model.lazy="email" @input="$v.email.$touch()">
            <div v-if="!$v.email.$pending && $v.email.$dirty && !$v.email.isUnique">Adres jest zajęty</div>
            <div v-if="$v.email.$dirty && !$v.email.email">Podaj poprawy email</div>
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="checkbox" v-model="terms">
            <label class="form-check-label" for="checkbox">Potwierdz</label>
          </div>
          <button :disabled="$v.$invalid" type="submit" class="btn btn-primary">Wyślij</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import { required, minLength, email } from 'vuelidate/lib/validators'
  import axios from "axios"

  export default {
    name: 'Form',
    data: function(){
      return {
        name: '',
        email: '',
        terms: false
      }
    },
    mixins: [validationMixin],
    validations: {
      name: {
        required,
        minLength: minLength(2)
      },
      email: {
        email,
        async isUnique(value){
          const response = await axios.get(lama_app.ajax_url, {
            config: {
              headers: {
                'Access-Control-Allow-Origin': '*',
              }
            }, 
            params: {
              action: 'lama_get_posts',
              nonce: lama_app.nonce,
              email: value
            }
          })
          return await Boolean(response.data.unique)
        }
      },
      terms: {
        required
      }
    }
  }
</script>

<style>
  label{
    display: block;
    cursor: pointer;
  }
</style>