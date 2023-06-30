import { defineStore } from "pinia";
// import axios from "axios";
import axios from "@/service/index";
import {
  getNewAccessToken,
  getUserInfo,
  keycloakCodeToToken,
  logoutUser
} from "@/service/userService";
import {
  getAccessTokenFromLocal,
  setAccessTokenToLocal,
  getRefreshTokenFromLocal,
  setRefreshTokenToLocal
} from "@/service/localService";
import { axiosRefreshToken } from "./axiosRefreshToken";
import { State } from "./type";

export const useUserStore = defineStore({
  id: "user",
  // can also be defined with an arrow function if you prefer that syntax
  state(): State {
    return {
      userInfo: {},
      userToken: "",
      accessToken: "",
      refreshToken: "",
      isLogin: false
    };
  },
  actions: {
    async setAccessToken(accessToken: string) {
      this.accessToken = accessToken;
      await setAccessTokenToLocal(accessToken);
    },
    async setRefreshToken(refreshToken: string) {
      this.refreshToken = refreshToken;
      await setRefreshTokenToLocal(refreshToken);
    },
    async logoutUser() {
      await logoutUser(this.accessToken, this.refreshToken);
      await Promise.all([setAccessTokenToLocal(null), setRefreshTokenToLocal(null)]);
      this.userInfo = {};
      this.userToken = "";
      this.isLogin = false;
      // return;
    },
    async rememberLogin() {
      const [accessToken, refreshToken] = await Promise.all([
        getAccessTokenFromLocal(),
        getRefreshTokenFromLocal()
      ]);
      if (accessToken === null || refreshToken === null) {
        console.log("They are no local tokens");
        throw new Error("They are no local tokens");
      }
      this.setAccessToken(accessToken);
      this.setRefreshToken(refreshToken);
      // 確定token的有效性才改變登入狀態
      try {
        await this.getUserInfo();
        this.isLogin = true;
        return "success";
      } catch (err) {
        this.logoutUser();
        throw err;
      }
    },
    async getUserInfo() {
      try {
        const userInfo = await getUserInfo(this.accessToken);
        this.userInfo = userInfo;
      } catch (err) {
        console.log("未登入或登入狀態無效");
        throw err;
      }
    },
    async getNewAccessToken() {
      const expiredAccessToken = this.accessToken;
      const { refreshToken } = this;
      try {
        const newAccessToken = await getNewAccessToken(expiredAccessToken, refreshToken);
        this.setAccessToken(newAccessToken);
        return newAccessToken;
      } catch (err) {
        this.logoutUser();
        throw err;
      }
    },
    async keycloakCodeToToken(code: string) {
      try {
        const tokens = await keycloakCodeToToken(code);
        console.log(tokens);
        this.setAccessToken(tokens.accessToken);
        this.setRefreshToken(tokens.refreshToken);
      } catch (err2) {
        console.log(`false:keycloak code to token error${err2}`);
        throw err2;
      }
    }
  }
});

axiosRefreshToken({
  axios,
  authorizeHeaderName: "X-Access-Tonken",
  errorHandlerCheck: (status, data) => status === 403 && data.statusCode === "Auth004",
  refreshTokenHandler: async () => {
    const userStore = useUserStore();
    const newAccessTocken = await userStore.getNewAccessToken();
    return newAccessTocken;
  }
});
