describe('E2E Testing Toko Online', () => {

    it('Login dan menampilkan halaman utama', () => {

        // Buka halaman login
        cy.visit('http://localhost:8000/login.php')

        // Isi email
        cy.get('input[name="email"]')
          .type('jaki@gmail.com')

        // Isi password
        cy.get('input[name="password"]')
          .type('12345')

        // Klik tombol login
        cy.contains('Login').click()

        // Validasi halaman berhasil login
        cy.contains('Toko Online')

    })

})