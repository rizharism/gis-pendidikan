/**
 * authModal – Alpine.js component for Login / Forgot / Reset flow.
 * Exposed on window so Alpine can pick it up via x-data="authModal()".
 * Uses axios for HTTP (auto CSRF via bootstrap.js X-XSRF-TOKEN header).
 */
export function authModal() {
    return {
        open: false,
        mode: 'login', // 'login' | 'forgot' | 'reset'

        // Login fields
        email: '',
        password: '',
        remember: false,

        // Forgot/Reset fields
        resetEmail: '',
        code: '',
        newPassword: '',
        confirmPassword: '',

        // UI state
        loading: false,
        error: '',
        toast: '',
        toastIsError: false,
        toastTimer: null,
        showPassword: false,
        showNewPassword: false,

        openModal() {
            this.open = true;
            this.mode = 'login';
            this.clearMessages();
            this.$nextTick(() => {
                const el = document.getElementById('auth-email');
                if (el) el.focus();
            });
        },

        closeModal() {
            this.open = false;
            this.clearMessages();
        },

        clearMessages() {
            this.error = '';
        },

        showToast(message, isError = false) {
            this.toast = message;
            this.toastIsError = isError;
            clearTimeout(this.toastTimer);
            this.toastTimer = setTimeout(() => { this.toast = ''; }, 4000);
        },

        // ─── LOGIN ───────────────────────────────────────────────────────
        async login() {
            this.error = '';
            this.loading = true;
            try {
                const { data } = await axios.post('/auth/login', {
                    email: this.email,
                    password: this.password,
                    remember: this.remember ? 1 : 0,
                });
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    this.error = data.error ?? 'Login gagal.';
                }
            } catch (err) {
                this.error = err.response?.data?.error ?? 'Terjadi kesalahan jaringan.';
            } finally {
                this.loading = false;
            }
        },

        // ─── SEND CODE ──────────────────────────────────────────────────
        async sendCode() {
            this.error = '';
            this.loading = true;
            try {
                const { data } = await axios.post('/auth/password/email', {
                    email: this.resetEmail,
                });
                if (data.sent) {
                    this.mode = 'reset';
                    this.showToast('Kode dikirim! Cek email Anda.');
                } else {
                    this.error = data.error ?? 'Gagal mengirim kode.';
                }
            } catch (err) {
                this.error = err.response?.data?.error ?? 'Terjadi kesalahan jaringan.';
            } finally {
                this.loading = false;
            }
        },

        async resendCode() {
            await this.sendCode();
        },

        // ─── RESET PASSWORD ─────────────────────────────────────────────
        async resetPassword() {
            this.error = '';
            this.loading = true;
            try {
                const { data } = await axios.post('/auth/password/reset', {
                    email: this.resetEmail,
                    code: this.code,
                    password: this.newPassword,
                    password_confirmation: this.confirmPassword,
                });
                if (data.success) {
                    this.showToast('Password berhasil direset! Silakan login.');
                    this.mode = 'login';
                    this.code = '';
                    this.newPassword = '';
                    this.confirmPassword = '';
                } else {
                    this.error = data.error ?? 'Gagal mereset password.';
                }
            } catch (err) {
                this.error = err.response?.data?.error ?? 'Terjadi kesalahan jaringan.';
            } finally {
                this.loading = false;
            }
        },
    };
}

window.authModal = authModal;
