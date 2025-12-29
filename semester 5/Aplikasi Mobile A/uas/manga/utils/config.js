const BASE_URL = "https://catss.my.id/13/manga-api";

export default {
  // Manga CRUD
  READ: `${BASE_URL}/read.php`,
  CREATE: `${BASE_URL}/create.php`,
  UPDATE: `${BASE_URL}/update.php`,
  DELETE: `${BASE_URL}/delete.php`,

  // Auth
  REGISTER: `${BASE_URL}/register.php`,
  LOGIN: `${BASE_URL}/login.php`,
  PROFILE: `${BASE_URL}/profile.php`,              // ✅ ambil data user
  CHANGE_PASSWORD: `${BASE_URL}/change-password.php`, // ✅ ganti password

  // Light Novel CRUD
  CREATE_LIGHTNOVEL: `${BASE_URL}/create_lightnovel.php`,
  READ_LIGHTNOVEL: `${BASE_URL}/read_lightnovel.php`,
  UPDATE_LIGHTNOVEL: `${BASE_URL}/update_lightnovel.php`,
  DELETE_LIGHTNOVEL: `${BASE_URL}/delete_lightnovel.php`,
};
