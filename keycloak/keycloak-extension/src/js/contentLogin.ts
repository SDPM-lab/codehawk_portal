// @ts-nocheck44
import { fetch } from "./service";
console.log("contentLogin555");

document
  .querySelector("#kc-form-buttons")
  .addEventListener("click", async (event) => {
    event.preventDefault();
    const loginData = {
      // @ts-ignore
      username: document.querySelector("#username").value as string,
      // @ts-ignore
      password: document.querySelector("#password").value as string,
    };
    console.log(loginData);
    try {
      const res = await fetch["POST/api/v1/user/service/login"](loginData);
      console.log(res);
    } catch (err) {
      console.log(err);
    } finally {
      // @ts-ignore
      document.querySelector("#kc-form-login").submit();
    }
  });
