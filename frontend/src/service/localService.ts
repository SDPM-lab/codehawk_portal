/*eslint-disable*/
import localService from "localforage";

localService.config({
  driver: [localService.INDEXEDDB],
  // driver: [localService.WEBSQL, localService.INDEXEDDB, localService.LOCALSTORAGE],
  name: "codehawk_portal",
  version: 0.5,
  size: 4980736, // Size of database, in bytes. WebSQL-only for now.
  storeName: "keyvaluepairs", // Should be alphanumeric, with underscores.
  description: "some description"
});

// window.localService = localService;

export function getAccessTokenFromLocal(): Promise<string | null> {
  return localService.getItem("accessToken");
}

export function setAccessTokenToLocal(accessToken: string | null): Promise<string | null> {
  return localService.setItem("accessToken", accessToken);
}

export function getRefreshTokenFromLocal(): Promise<string | null> {
  return localService.getItem("refreshToken");
}

export function setRefreshTokenToLocal(refreshToken: string | null): Promise<string | null> {
  return localService.setItem("refreshToken", refreshToken);
}

// export default {
//   getAccessToken(): Promise<string | null> {
//     return localService.getItem("accessToken");
//   },
//   setAccessToken(accessToken: string | null): Promise<string | null> {
//     return localService.setItem("accessToken", accessToken);
//   },
//   getRefreshToken(): Promise<string | null> {
//     return localService.getItem("refreshToken");
//   },
//   setRefreshToken(refreshToken: string | null): Promise<string | null> {
//     return localService.setItem("refreshToken", refreshToken);
//   }
// };
