package handler

import (
	"latihan-fiber-api/internal/repository"

	"github.com/gofiber/fiber/v3"
)

func DaftarProgramStudi(c fiber.Ctx) error {
	daftar, err := repository.AmbilProgramStudiDenganJumlahMahasiswa()
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil data program studi")
	}
	return c.JSON(fiber.Map{
		"sukses": true,
		"data":   daftar,
	})
}
