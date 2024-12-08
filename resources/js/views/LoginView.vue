<script setup>
  import { reactive } from 'vue';
  import { useRouter } from 'vue-router';
  import { mdiAccount, mdiAsterisk } from '@mdi/js';
  import SectionFullScreen from '@/components/SectionFullScreen.vue';
  import CardBox from '@/components/CardBox.vue';
  import FormCheckRadio from '@/components/FormCheckRadio.vue';
  import FormField from '@/components/FormField.vue';
  import FormControl from '@/components/FormControl.vue';
  import BaseButton from '@/components/BaseButton.vue';
  import BaseButtons from '@/components/BaseButtons.vue';
  import LayoutGuest from '@/layouts/LayoutGuest.vue';
  import Swal from 'sweetalert2';
  import { useUserStore } from '@/stores/user';

  const form = reactive({
    email: 'user@localhost.com',
    password: 'password',
    remember: true,
  });

  const router = useRouter();
  const userStore = useUserStore();

  const submit = async () => {
    try {
      await axios.post('/login', form);
      await userStore.fetchUser();
      router.push('/');
    } catch (error) {
      console.log(error);
      Swal.fire('Failed to login. Something went wrong');
    }
  };
</script>

<template>
  <LayoutGuest>
    <SectionFullScreen v-slot="{ cardClass }" bg="purplePink">
      <CardBox :class="cardClass" is-form @submit.prevent="submit">
        <FormField label="Login" help="Please enter your login">
          <FormControl
            v-model="form.email"
            :icon="mdiAccount"
            name="login"
            type="email"
            autocomplete="email"
          />
        </FormField>

        <FormField label="Password" help="Please enter your password">
          <FormControl
            v-model="form.password"
            :icon="mdiAsterisk"
            type="password"
            name="password"
            autocomplete="password"
          />
        </FormField>

        <FormCheckRadio
          v-model="form.remember"
          name="remember"
          label="Remember me"
          :input-value="true"
        />

        <template #footer>
          <BaseButtons>
            <BaseButton type="submit" color="info" label="Login" />
          </BaseButtons>
        </template>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
