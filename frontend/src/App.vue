<template>
  <div id="nav">
    <button @click="logout">logout</button><br />
    <button @click="login">login</button><br />
    <router-link to="/">Home</router-link> |
    <router-link to="/about">About</router-link>
  </div>
  <!-- <router-view /> -->
</template>

<script>
import { defineComponent } from "vue";
import { useUserStore } from "@/store/userPinia/index";
import ssoUserCheck from "@/composables/ssoUserCheck";

export default defineComponent({
  name: "APP",
  setup() {
    console.log("codehawk frontend app init");
    const userStore = useUserStore();
    const login = async () => {
      try {
        const res = await ssoUserCheck();
        console.log(res);
      } catch (err) {
        console.log("login false");
      }
    };

    const logout = async () => {
      try {
        await userStore.logoutUser();
        alert("logout success");
        window.location.href = "http://localhost:9000/";
      } catch (err) {
        console.log(err);
      }
    };
    return {
      logout,
      login
    };
  }
});
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
