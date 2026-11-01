package router

import (
	"latihan-fiber-api/internal/handler"
	"latihan-fiber-api/internal/middleware"

	"github.com/gofiber/fiber/v3"
)

func Daftarkan(app *fiber.App, apiKey string) {
	api := app.Group("/api")
	api.Get("/status", func(c fiber.Ctx) error {
		return c.JSON(fiber.Map{
			"sukses": true,
			"pesan":  "API Fiber aktif",
		})
	})
	mahasiswa := api.Group("/mahasiswa")
	mahasiswa.Use(middleware.KunciApi(apiKey))
	mahasiswa.Get("/", handler.DaftarMahasiswa)
	mahasiswa.Get("/nim/:nim", handler.DetailMahasiswaNIM)
	mahasiswa.Get("/:id", handler.DetailMahasiswa)
}
