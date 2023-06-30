import { createPinia } from "pinia";
import { useUserStore } from "@/store/userPinia/index";
import * as userService from "@/service/userService";
import * as localService from "@/service/localService";

jest.mock("@/service/userService");
const mockedUserService = userService as jest.Mocked<typeof userService>;
const { getUserInfo, getNewAccessToken } = mockedUserService;

jest.mock("@/service/localService");
const mockedLocalService = localService as jest.Mocked<typeof localService>;
const {
  getAccessTokenFromLocal,
  setAccessTokenToLocal,
  getRefreshTokenFromLocal,
  setRefreshTokenToLocal
} = mockedLocalService;

// get promise resolve value type
type Await<T> = T extends PromiseLike<infer U> ? U : T;

/**
 * mock dependency
 */
const fakeUserInfo: Await<ReturnType<typeof getUserInfo>> = {
  first_name: "Sam",
  last_name: "Chen",
  email: "sam123456@mail.com",
  username: "sam123456"
};

const tokenString = "token";
const mockSetAccessToken = jest.fn();
const mockSetRefreshToken = jest.fn();

const mocksetAccessTokenToLocal = jest.fn();
setAccessTokenToLocal.mockImplementation(mocksetAccessTokenToLocal);

const mockSetRefreshTokenToLocal = jest.fn();
setRefreshTokenToLocal.mockImplementation(mockSetRefreshTokenToLocal);

getUserInfo.mockResolvedValue(Promise.resolve(fakeUserInfo));
getAccessTokenFromLocal.mockResolvedValue(Promise.resolve(tokenString));
getRefreshTokenFromLocal.mockResolvedValue(Promise.resolve(tokenString));
getNewAccessToken.mockResolvedValue(Promise.resolve(tokenString));

/**
 * start test
 */
describe("setTokens", () => {
  it("set access token to state and local", async () => {
    const userStore = useUserStore(createPinia());
    await userStore.setAccessToken(tokenString);
    expect(userStore.accessToken).toStrictEqual(tokenString);
    expect(mocksetAccessTokenToLocal.mock.calls[0][0]).toBe(tokenString);
  });

  it("set refresh token to state and local", async () => {
    const userStore = useUserStore(createPinia());
    await userStore.setRefreshToken(tokenString);
    expect(userStore.refreshToken).toStrictEqual(tokenString);
    expect(mockSetRefreshTokenToLocal.mock.calls[0][0]).toBe(tokenString);
  });
});

describe("getUserInfo", () => {
  it("set userInfo to state when get success", async () => {
    const userStore = useUserStore(createPinia());
    userStore.accessToken = "someTokenText";
    await userStore.getUserInfo();
    expect(userStore.userInfo).toStrictEqual(fakeUserInfo);
  });
});

describe("logoutUser", () => {
  it("remove login state and local token when logout success", async () => {
    const userStore = useUserStore(createPinia());
    userStore.$patch({
      userInfo: fakeUserInfo,
      userToken: tokenString,
      isLogin: true
    });
    await userStore.logoutUser();
    expect(userStore.userInfo).toStrictEqual({});
    expect(userStore.userToken).toBe("");
    expect(userStore.isLogin).toBe(false);
    expect(mocksetAccessTokenToLocal.mock.calls[0][0]).toBe(tokenString);
    expect(mockSetRefreshTokenToLocal.mock.calls[0][0]).toBe(tokenString);
  });
});

describe("rememberLogin", () => {
  it("throw reject when access local token is null", async () => {
    getAccessTokenFromLocal.mockResolvedValueOnce(Promise.resolve(null));
    const userStore = useUserStore(createPinia());
    await expect(userStore.rememberLogin()).rejects.toThrow("They are no local tokens");
  });

  it("throw reject when refresh local token is null", async () => {
    getRefreshTokenFromLocal.mockResolvedValueOnce(Promise.resolve(null));
    const userStore = useUserStore(createPinia());
    await expect(userStore.rememberLogin()).rejects.toThrow("They are no local tokens");
  });

  it("set userInfo state and call setTokens when login success", async () => {
    const userStore = useUserStore(createPinia());
    userStore.setAccessToken = mockSetAccessToken;
    userStore.setRefreshToken = mockSetRefreshToken;
    await userStore.rememberLogin();
    expect(userStore.userInfo).toStrictEqual(fakeUserInfo);
    expect(mockSetAccessToken.mock.calls[0][0]).toBe(tokenString);
    expect(mockSetRefreshToken.mock.calls[0][0]).toBe(tokenString);
  });

  it("call logout when getUserInfo reject", async () => {
    getUserInfo.mockResolvedValueOnce(Promise.reject());
    const userStore = useUserStore(createPinia());
    const mockLogoutUser = jest.fn();
    userStore.logoutUser = mockLogoutUser;
    try {
      await userStore.rememberLogin();
    } catch (err) {
      expect(mockLogoutUser.mock.calls.length).toBe(1);
    }
  });
});

describe("getNewAccessToken", () => {
  it("call setAccessToken with tokenString when get new token success", async () => {
    const userStore = useUserStore(createPinia());
    userStore.setAccessToken = mockSetAccessToken;
    await userStore.getNewAccessToken();
    expect(mockSetAccessToken.mock.calls[0][0]).toBe(tokenString);
  });

  it("call logout when get access token false", async () => {
    getNewAccessToken.mockResolvedValueOnce(Promise.reject());
    const userStore = useUserStore(createPinia());
    const mockLogoutUser = jest.fn();
    userStore.logoutUser = mockLogoutUser;
    try {
      await userStore.getNewAccessToken();
    } catch (err) {
      expect(mockLogoutUser.mock.calls.length).toBe(1);
    }
  });
});
