/*eslint-disable*/
import { useUserStore } from "@/store/userPinia/index";

const ssoLoginPage = `${process.env.VUE_APP_API_BACKEND_URL}/api/v1/user/login`;

export default () => {
  return new Promise<string>(async (resolve, reject) => {
    console.log("try REMEMBER_LOGIN");
    const userStore = useUserStore();
    try {
      await userStore.rememberLogin();
      resolve("rememberLogin success");
    } catch (err) {
      // get url SSO code
      const urlString = window.location.href;
      const url = new URL(urlString);
      const code = url.searchParams.get("code");
      if (!code) {
        // There is no sso code, redirect to sso login page
        window.location.href = ssoLoginPage;
      } else {
        console.log(`get sso code: ${code}`);
        try {
          await userStore.keycloakCodeToToken(code);
          resolve("sso success");
        } catch (err2) {
          alert("server error when code to token");
          // window.location.href = ssoLoginPage;
          reject("sso false:" + err2);
        }
      }
    }
  });
};
