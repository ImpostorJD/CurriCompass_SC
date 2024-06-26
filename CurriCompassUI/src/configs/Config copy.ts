/**
 * 3/1/2024
 *
 * Copy this and rename the copied file as Config.ts
 *
 * Configuration file that serves as .env for frontend.
 *
 * type your ip address and local on the apiURls.[Environment.LOCAL] as the value if you
 * want to use it to your network, otherwise leave empty.
 *
 * Set both the environment and apiUrl of environment as EnvironmentType.DEV
 *
 */
enum EnvironmentType {
    LOCAL = "local",
    PROD = "prod",
    DEV = "dev",
  }
  const apiUrls: Record<EnvironmentType, string> = {
    [EnvironmentType.PROD]: "http://www.example.com",
    [EnvironmentType.LOCAL]: "",
    [EnvironmentType.DEV]: "http://127.0.0.1:8000/api/"
  };

  export const environment = {
    environment: EnvironmentType.DEV,
    apiUrl: apiUrls[EnvironmentType.DEV],
  };

