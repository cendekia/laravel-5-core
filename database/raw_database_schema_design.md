# Raw Database Schema Design

## User and Role table

There are 4 type of registered users:
    1. Customer
    2. Agen (part of admin role)
    3. LGI (part of admin role)
    4. Admin (this include all custom admin roles);

### Tables

users           |role_user (pivot)    | roles
----------------|---------------------|------
id              |user_id              |id
name            |role_id              |slug
email           |registered_ip_address|parent_role_id
password        |                     |name
remember_token  |                     |permission
                |                     |whitelisted_ip_addresses

admin_profile   |
----------------|
id              |
profile_picture |
user_id         |

subscribes      |
----------------|
id              |
email           |
active          |
