import axios from "@/service/index";
import { fetch, Models } from "./rapper";

export async function getUserInfo(
  accessToken: string
): Promise<Models["GET/api/v1/user/:username"]["Res"]["data"]> {
  axios.defaults.headers.common["X-Access-Token"] = accessToken;
  const res = await fetch["GET/api/v1/user/:username"]({
    username: "sam"
  });
  return res.data;
}

export async function getNewAccessToken(
  expiredAccessToken: string,
  refreshToken: string
): Promise<string> {
  axios.defaults.headers.common["X-Access-Tonken"] = expiredAccessToken;
  axios.defaults.headers.common["X-Refresh-Tonken"] = refreshToken;
  const res = await fetch["PUT/api/v1/user/refresh"]();
  const { access_token: newAccessToken } = res;
  return newAccessToken;
}

export async function keycloakCodeToToken(
  oidcCode: string
): Promise<Models["POST/api/v1/user/login"]["Res"]["data"]> {
  axios.defaults.headers.common["X-Oidc-Code"] = oidcCode;
  // const { data } = await fetch["POST/api/v1/user/login"]();
  const res = await fetch["POST/api/v1/user/login"]();
  delete axios.defaults.headers.common["X-Oidc-Code"];
  console.log(res);
  console.log(res.data);
  return res.data;
}

export async function logoutUser(accessToken: string, refreshToken: string) {
  axios.defaults.headers.common["X-Refresh-Token"] = refreshToken;
  const res = await fetch["DELETE/api/v1/user/:accessToken"]({
    accessToken
  });
}

// const userService = {
// 取得使用者資料
// async getUserInfo(
//   accessToken: string
// ): Promise<Models["GET/api/v1/user/:username"]["Res"]["data"]> {
//   console.log(555);
//   // !
//   axios.defaults.baseURL = process.env.VUE_APP_API_MOCK_URL;
//   // !
//   axios.defaults.headers.common["X-Access-Token"] = accessToken;
//   const res = await fetch["GET/api/v1/user/:username"]({
//     username: "sam"
//   });
//   // !
//   axios.defaults.baseURL = process.env.VUE_APP_API_MOCK_URL;
//   // !
//   return res.data;
// },
// // 修改使用者資料
// async modifyUserInfo(payload: any) {
//   // 轉換型別
//   payload.business = payload.business === "true";
//   const res = await axios({
//     method: "put",
//     url: "/user",
//     data: payload
//   });
//   return res.data.data;
// },
// 更新 accessTocken
// async getNewAccessToken(expiredAccessToken: string, refreshToken: string): Promise<string> {
//   axios.defaults.headers.common["X-Access-Tonken"] = expiredAccessToken;
//   axios.defaults.headers.common["X-Refresh-Tonken"] = refreshToken;
//   const res = await fetch["PUT/api/v1/user/refresh"]();
//   const { access_token: newAccessToken } = res;
//   return newAccessToken;
// },
// keycloak code to token
// async keycloakCodeToToken(
//   oidcCode: string
// ): Promise<Models["POST/api/v1/user/login"]["Res"]["data"]> {
//   axios.defaults.headers.common["X-Oidc-Code"] = oidcCode;
//   // const { data } = await fetch["POST/api/v1/user/login"]();
//   const res = await fetch["POST/api/v1/user/login"]();
//   delete axios.defaults.headers.common["X-Oidc-Code"];
//   console.log(res);
//   console.log(res.data);
//   return res.data;
// }
// };
// export default userService;
