// @ts-nocheck
import { fetch } from "./service";
console.log("contentRegister");

document
  .querySelector("#kc-form-buttons")
  .addEventListener("click", async (event) => {
    event.preventDefault();
    const registerData = {
      first_name: document.querySelector("#firstName").value as string,
      last_name: document.querySelector("#lastName").value as string,
      email: document.querySelector("#email").value as string,
      password: document.querySelector("#password").value as string,
      confirm_password: document.querySelector("#password-confirm")
        .value as string,
    };
    console.log(registerData);
    try {
      const res = await fetch["POST/api/v1/user/service"]();
      console.log(res);
      // 成功就跳轉回 login 頁面
      const linkToLogin = document.querySelector("#kc-form-options a");
      linkToLogin.click();
    } catch (err) {
      document.querySelector("#kc-register-form").submit();
    }
  });
