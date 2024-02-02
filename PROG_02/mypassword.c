#include <stdio.h>
#include <string.h>
#include <unistd.h>
#include <stdlib.h>
#include <pwd.h>
#include <shadow.h>
#include <time.h>

void change_shadowfile_entry(char *username, struct spwd *new_entry){
	FILE *temp_file = tmpfile();

	struct spwd *shadow_entry;

	// Loop through the shadow file
	while ((shadow_entry = getspent()) != NULL){
		if (strcmp(shadow_entry->sp_namp, username) == 0) {
			// if meet a user that needs to be changed, replace with a new entry 
            putspent(new_entry, temp_file);
        } else {
        	// else, remain the same
        	putspent(shadow_entry, temp_file);
        }
	}

	// Move the content from the temporary file to the shadow file.
	rewind(temp_file);

	FILE *shadow_file = fopen("/etc/shadow", "w");

	char line[256];

	while (fgets(line, sizeof(line), temp_file) != NULL) {
        fputs(line, shadow_file);
    }

    fclose(temp_file);
    fclose(shadow_file);
}

void change_password() {

    char *old_password;
    uid_t user_id = getuid();
    struct passwd *user_info = getpwuid(user_id);
    struct spwd *shadow_entry = getspnam(user_info->pw_name);

    // Enter the old password
    old_password = getpass("Enter old password: ");
    printf("------------------------------------------------------\n");

    // Check if old password correct, enter new password and change
    if (strcmp(shadow_entry->sp_pwdp, crypt(old_password, shadow_entry->sp_pwdp)) == 0) {
    	
        char *new_password;
        new_password = getpass("Old password is correct, enter new password: ");

        // Change the encrypt password of entry
        shadow_entry->sp_pwdp = crypt(new_password, shadow_entry->sp_pwdp);

        //printf("%s\n", shadow_entry->sp_pwdp);
        

        // Change the last password change date
        time_t current_Unix_second = time(NULL);
        long current_Unix_day = (long) current_Unix_second / (24 * 60 * 60);
        shadow_entry->sp_lstchg = current_Unix_day;

        change_shadowfile_entry(user_info->pw_name, shadow_entry);

        printf("Change password successfully\n");

    } else {
        printf("Wrong password\n");
    }

}

int main() {
    change_password();
    return 0;
}