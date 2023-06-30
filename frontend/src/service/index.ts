import axios from "axios";
import { overrideFetch } from "./rapper/index";

axios.defaults.baseURL = process.env.VUE_APP_API_MOCK_URL;
// axios.defaults.baseURL = process.env.VUE_APP_API_BACKEND_URL;

overrideFetch(
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  async ({ url, method, params }): Promise<any> => {
    try {
      const response = await axios({
        method,
        url,
        data: params
      });
      return response.data;
    } catch (err) {
      console.error(err);
      throw err;
    }
  }
);

export default axios;
