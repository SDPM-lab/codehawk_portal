/*eslint-disable*/
import { AxiosStatic, AxiosRequestConfig, AxiosError } from "axios";

interface TaskItem {
  originalRequest: AxiosRequestConfig;
  resolve: <T>(value?: T | PromiseLike<T>) => void;
  reject: (reason?: any) => void;
}

interface AxiosRefreshToken {
  axios: AxiosStatic;
  authorizeHeaderName: string;
  errorHandlerCheck: (status: number, data: any) => boolean;
  refreshTokenHandler: () => Promise<string>;
}

const axiosRefreshToken = (config: AxiosRefreshToken) => {
  const { axios, authorizeHeaderName, errorHandlerCheck, refreshTokenHandler } = config;
  let isRefreshing = false;
  let tasks: TaskItem[] = [];

  axios.interceptors.response.use(
    response => {
      return response;
    },
    async (err: AxiosError) => {
      if (err.response === undefined) return Promise.reject(err);
      const {
        config,
        response: { status, data }
      } = err;
      if (errorHandlerCheck(status, data)) {
        // 尚未開始更新即啟動更新
        if (!isRefreshing) {
          isRefreshing = true;
          try {
            const newAccessToken = await refreshTokenHandler();
            isRefreshing = false;
            tasks.forEach(item => {
              item.originalRequest.headers[authorizeHeaderName] = newAccessToken;
              item.resolve(axios(item.originalRequest));
            });
          } catch (err) {
            isRefreshing = false;
            tasks.forEach(item => {
              item.reject(err);
            });
          }
        }

        // 回傳promise並把其放置在等待區等候token更新
        return new Promise((resolve, reject) => {
          tasks.push({
            originalRequest: config,
            resolve,
            reject
          });
        });
      } else {
        return Promise.reject(err);
      }
    }
  );
};

export { axiosRefreshToken };
