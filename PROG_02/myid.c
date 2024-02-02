#include <stdio.h>
#include <pwd.h>
#include <grp.h>

void user_info_by_uname(char *username) {
    printf("-----------------------------------\n");
    struct passwd *user_info = getpwnam(username);

    if (user_info != NULL) {
        // Print User info
        printf("User ID: %d\n", user_info->pw_uid);
        printf("Username: %s\n", user_info->pw_name);
        printf("Home directory: %s\n", user_info->pw_dir);

        // Print Group info
        printf("Group: ");

        int num_of_group = 0;
        struct group *grp;

        getgrouplist(user_info->pw_name, user_info->pw_gid, NULL, &num_of_group);

        gid_t groups[num_of_group];

        getgrouplist(user_info->pw_name, user_info->pw_gid, groups, &num_of_group);

        for (int i = 0; i < num_of_group; i++) {
            grp = getgrgid(groups[i]);
            if (grp != NULL) {
                printf("%s", grp->gr_name);
                if (i != num_of_group - 1) {
                    printf(", ");
                }
            }
        }

        printf("\n");

    } else {
        printf("User not found\n");
    }
}

int main() {
    char username[100];
    printf("Enter username: ");
    scanf("%s", username);

    user_info_by_uname(username);

    return 0;
}