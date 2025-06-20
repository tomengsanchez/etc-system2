# Ecosys Training Center - Enrollment System

## Secure Roles API

This system now includes a secure REST API for managing user roles. All endpoints require a valid JSON Web Token (JWT) for authentication and are restricted to users with the 'admin' role.

### How to Authenticate

1.  **Obtain a Token**: Send a `POST` request to the `/api/login` endpoint with a valid admin username and password in the JSON body.

    ```json
    {
      "email": "admin@example.com",
      "password": "adminpassword"
    }
    ```

2.  **Receive the Token**: If the credentials are correct, the API will return a JWT.

    ```json
    {
      "message": "Successful login.",
      "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ..."
    }
    ```

### Accessing Protected Endpoints

To access the roles API, include the received token in the `Authorization` header of your request, using the `Bearer` schema.

**Header Example:**
`Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ...`

### API Endpoints

All endpoints are relative to `/api/roles`.

* **`GET /`**
    * **Description**: Fetches a list of all roles.
    * **Requires**: Valid Admin JWT.

* **`POST /`**
    * **Description**: Creates a new role.
    * **Requires**: Valid Admin JWT.
    * **Body**:
        ```json
        {
          "name": "NewRoleName"
        }
        ```

* **`PUT /{id}`**
    * **Description**: Updates an existing role's name.
    * **Requires**: Valid Admin JWT.
    * **Body**:
        ```json
        {
          "name": "UpdatedRoleName"
        }
        ```

* **`DELETE /{id}`**
    * **Description**: Deletes a role by its ID.
    * **Requires**: Valid Admin JWT.

### Testing with Postman

Here’s a step-by-step guide to testing the API using Postman.

**Step 1: Get Your Authentication Token**

1.  Open Postman and create a new request.
2.  Set the method to **POST**.
3.  Enter the URL: `https://ecosyscorp.ph/etc-system/api/login`
4.  Go to the **Body** tab, select **raw**, and choose **JSON** from the dropdown.
5.  In the text area, paste the admin credentials:
    ```json
    {
      "email": "admin@example.com",
      "password": "adminpassword"
    }
    ```
6.  Click **Send**. If successful, you will see a JSON response containing a `token`. Copy this token value.

**Step 2: Access a Protected Route (e.g., Get All Roles)**

1.  Create another new request in Postman.
2.  Set the method to **GET**.
3.  Enter the URL for the roles endpoint: `https://ecosyscorp.ph/etc-system/api/roles`
4.  Go to the **Authorization** tab.
5.  Select **Bearer Token** from the **Type** dropdown.
6.  Paste the token you copied from the login step into the **Token** field on the right.
7.  Click **Send**. You should now see the list of roles in the response body.

**Step 3: Test a POST Request (e.g., Create a Role)**

1.  Create a new request. Set the method to **POST** and the URL to `https://ecosyscorp.ph/etc-system/api/roles`.
2.  Go to the **Authorization** tab and add the Bearer Token, just like in Step 2.
3.  Go to the **Body** tab, select **raw** and **JSON**.
4.  Enter the data for the new role:
    ```json
    {
      "name": "Instructor"
    }
    ```
5.  Click **Send**. You should receive a success message. You can verify this by running the GET request from Step 2 again.

---
**IMPORTANT SECURITY NOTE:**
You must install the `firebase/php-jwt` library. You can do this by running `composer require firebase/php-jwt` in your project's root directory. Also, remember to change the `JWT_SECRET` in `app/config/config.php` to a strong, unique secret key.
