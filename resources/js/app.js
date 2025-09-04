import './bootstrap';

// เราจะไม่ import Alpine จากที่นี่อีกต่อไป เพราะ Livewire จัดการให้แล้ว

function userManager() {
    return {
        showEditModal: false,
        isLoading: false,
        notification: { show: false, message: '', type: 'success' },
        dropdowns: { positions: [], departments: [], locations: [], roles: [] },
        currentUserId: null,
        editFormData: { 
            name: '', 
            email: '', 
            role: '', 
            employee: {
                employee_id: '', first_name: '', last_name: '',
                position: null, department: null, location: null,
                phone_number: '', start_date: ''
            } 
        },

        openEditModal(userId) {
            this.currentUserId = userId;
            const url = `/users/${userId}/edit-data`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.user && data.user.employee) {
                        const emp = data.user.employee;
                        emp.position   = emp.position ? emp.position.trim() : null;
                        emp.department = emp.department ? emp.department.trim() : null;
                        emp.location   = emp.location ? emp.location.trim() : null;
                    }

                    this.editFormData = data.user;
                    if (!this.editFormData.employee) { 
                        this.editFormData.employee = {
                            employee_id: '', first_name: '', last_name: '',
                            position: null, department: null, location: null,
                            phone_number: '', start_date: ''
                        }; 
                    }
                    this.dropdowns = {
                        positions: data.positions,
                        departments: data.departments,
                        locations: data.locations,
                        roles: data.roles
                    };
                    
                    this.$nextTick(() => {
                        this.showEditModal = true;
                    });
                })
                .catch(error => {
                    console.error('Error fetching edit data:', error);
                    this.showNotification('Could not load user data.', 'error');
                });
        },

        // ... (ส่วน submitEditForm และ showNotification เหมือนเดิม) ...
        submitEditForm() {
            this.isLoading = true;
            const formData = new FormData(this.$refs.editForm);
            formData.append('_method', 'PUT');
            
            fetch(`/users/${this.currentUserId}`, {
                method: 'POST',
                headers: { 
                    'Accept': 'application/json', 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) { throw data; }
                location.reload(); 
            })
            .catch(error => {
                this.showNotification(error.message || 'An error occurred while updating.', 'error');
            })
            .finally(() => {
                this.isLoading = false;
            });
        },

        showNotification(message, type) {
            this.notification.message = message;
            this.notification.type = type;
            this.notification.show = true;
            setTimeout(() => { this.notification.show = false; }, 5000);
        }
    }
}

// [สำคัญมาก] เราจะใช้ Event Listener เพื่อรอให้ Alpine พร้อมใช้งาน
// นี่คือวิธีที่ถูกต้องสำหรับโปรเจกต์ที่มี Livewire
document.addEventListener('alpine:init', () => {
    Alpine.data('userManager', userManager);
})
