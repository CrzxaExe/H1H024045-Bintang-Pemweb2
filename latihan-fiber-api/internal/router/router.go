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
	mahasiswa.Post("/", handler.BuatMahasiswa)
	mahasiswa.Get("/:id", handler.DetailMahasiswa)
	mahasiswa.Put("/:id", handler.PerbaruiMahasiswa)
	mahasiswa.Delete("/:id", handler.HapusMahasiswa)
	mahasiswa.Post("/:id/pulihkan", handler.PulihkanMahasiswa)

	programStudi := api.Group("/program-studi")
	programStudi.Use(middleware.KunciApi(apiKey))
	programStudi.Get("/", handler.DaftarProgramStudi)

	matakuliah := api.Group("/matakuliah")
	matakuliah.Use(middleware.KunciApi(apiKey))
	matakuliah.Get("/", handler.DaftarMatakuliah)
	matakuliah.Post("/", handler.BuatMatakuliah)
	matakuliah.Get("/:id", handler.DetailMatakuliah)
	matakuliah.Put("/:id", handler.PerbaruiMatakuliah)
	matakuliah.Delete("/:id", handler.HapusMatakuliah)
}
